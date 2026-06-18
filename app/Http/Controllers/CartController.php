<?php
namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * CartController
 * FIX: Cart now persisted in DB (carts table) per user — survives logout/login
 * OWASP ASVS V4 — IDOR Prevention, all cart rows scoped to Auth::id()
 */
class CartController extends Controller
{
    public function index()
    {
        $items = Cart::with('product')->where('user_id', Auth::id())->get();
        $total = $items->sum(fn($i) => $i->product->price * $i->quantity);
        return view('cart.index', compact('items', 'total'));
    }

    public function add(Request $request, Product $product)
    {
        $request->validate(['quantity' => ['required','integer','min:1','max:99']]);
        if (!$product->is_active) { abort(404); }

        $qty = min((int)$request->quantity, $product->stock);

        $cartItem = Cart::where('user_id', Auth::id())
                        ->where('product_id', $product->id)
                        ->first();

        if ($cartItem) {
            $newQty = min($cartItem->quantity + $qty, $product->stock);
            $cartItem->update(['quantity' => $newQty]);
        } else {
            Cart::create([
                'user_id'    => Auth::id(),
                'product_id' => $product->id,
                'quantity'   => $qty,
            ]);
        }

        if ($request->has('buy_now')) {
            return redirect()->route('cart.index');
        }

        return redirect()->back()->with('success', "\"{$product->name}\" added to cart!");
    }

    public function updateQty(Request $request, $productId)
    {
        $request->validate(['quantity' => ['required','integer','min:1','max:99']]);
        $item = Cart::where('user_id', Auth::id())->where('product_id', (int)$productId)->firstOrFail();
        $item->update(['quantity' => min((int)$request->quantity, $item->product->stock)]);
        return redirect()->route('cart.index');
    }

    public function remove($productId)
    {
        Cart::where('user_id', Auth::id())->where('product_id', (int)$productId)->delete();
        return redirect()->route('cart.index')->with('success', 'Item removed.');
    }

    public function clear()
    {
        Cart::where('user_id', Auth::id())->delete();
        return redirect()->route('cart.index')->with('success', 'Cart cleared.');
    }

    public function checkout(Request $request)
    {
        $items = Cart::with('product')->where('user_id', Auth::id())->get();

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $request->validate([
            'address'  => ['required','string','min:5','max:500'],
            'phone'    => ['required','string','regex:/^[0-9\+\-\s]{8,20}$/'],
            'notes'    => ['nullable','string','max:500'],
            'payment'  => ['required','in:cod,online_banking,credit_card,ewallet'],
        ]);

        try {
            DB::transaction(function() use ($items, $request) {
                $total = 0;
                $orderItems = [];

                foreach ($items as $item) {
                    $product = Product::lockForUpdate()->findOrFail($item->product_id);
                    if ($product->stock < $item->quantity) {
                        throw new \Exception("Sorry, \"{$product->name}\" only has {$product->stock} units left.");
                    }
                    $total += $product->price * $item->quantity;
                    $product->decrement('stock', $item->quantity);
                    $orderItems[] = [
                        'product_id' => $product->id,
                        'quantity'   => $item->quantity,
                        'unit_price' => $product->price,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                $order = Order::create([
                    'user_id'          => Auth::id(),
                    'total_amount'     => $total,
                    'status'           => 'pending',
                    'shipping_address' => $request->address,
                    'phone'            => $request->phone,
                    'payment_method'   => $request->payment,
                    'notes'            => strip_tags($request->input('notes', '')),
                ]);

                $order->items()->insert(
                    array_map(fn($i) => array_merge($i, ['order_id' => $order->id]), $orderItems)
                );

                // Clear DB cart after successful order
                Cart::where('user_id', Auth::id())->delete();

                AuditLog::record('order_placed', "Order #{$order->id} placed. Total: RM".number_format($total,2), Auth::id());
            });

            return redirect()->route('orders.index')->with('success', 'Order placed successfully! 🎉');

        } catch (\Exception $e) {
            return redirect()->route('cart.index')->with('error', $e->getMessage());
        }
    }
}
