<?php
namespace App\Http\Controllers;
use App\Models\AuditLog;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Auth::user()->orders()
            ->with('items.product')
            ->orderBy('created_at','desc')
            ->paginate(10);
        return view('orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Auth::user()->orders()->with('items.product')->findOrFail((int)$id);
        return view('orders.show', compact('order'));
    }

    public function cancel($id)
    {
        $order = Auth::user()->orders()->findOrFail((int)$id);
        if (!in_array($order->status, ['pending','processing'])) {
            return back()->with('error', 'This order cannot be cancelled.');
        }
        // Restore stock
        foreach ($order->items()->with('product')->get() as $item) {
            $item->product->increment('stock', $item->quantity);
        }
        $order->update(['status' => 'cancelled']);
        AuditLog::record('order_cancelled', "Order #{$order->id} cancelled by user.", Auth::id());
        return back()->with('success', 'Order cancelled.');
    }
}
