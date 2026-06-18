@extends('layouts.app')
@section('title','Shopping Cart')
@section('content')
<div class="page-wrap">
  <div style="font-size:16px;font-weight:700;margin-bottom:16px">🛒 Shopping Cart ({{ count($items) }} item{{ count($items)!=1?'s':'' }})</div>

  @if($items->isEmpty())
    <div class="card" style="text-align:center;padding:60px 20px">
      <div style="font-size:5rem;margin-bottom:16px">🛒</div>
      <div style="font-size:16px;font-weight:600;margin-bottom:8px">Your cart is empty</div>
      <p style="color:var(--muted);margin-bottom:20px">Looks like you haven't added anything yet.</p>
      <a href="{{ route('products.index') }}" class="btn btn-orange">Start Shopping</a>
    </div>
  @else
    <div class="checkout-wrap">
      {{-- Cart items --}}
      <div>
        <div style="display:flex;justify-content:flex-end;margin-bottom:8px">
          <form method="POST" action="{{ route('cart.clear') }}">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-white btn-sm" onclick="return confirm('Clear entire cart?')">🗑 Clear Cart</button>
          </form>
        </div>

        @foreach($items as $item)
        <div class="cart-item">
          <div class="cart-thumb">{{ $item->product->emoji }}</div>
          <div class="cart-details">
            <div class="cart-name">{{ $item->product->name }}</div>
            <div style="font-size:12px;color:var(--muted);margin-bottom:4px">{{ ucfirst($item->product->category) }}</div>
            <div class="cart-price">RM {{ number_format($item->product->price,2) }}</div>
            {{-- Qty controls --}}
            <form method="POST" action="{{ route('cart.updateQty',$item->product_id) }}" style="display:inline" id="form-qty-{{ $item->product_id }}">
              @csrf @method('PATCH')
              <div class="qty-ctrl mt-1">
                <button type="button" class="qty-btn" onclick="updateQty({{ $item->product_id }},{{ $item->quantity-1 }})" style="border-radius:4px 0 0 4px">−</button>
                <input type="number" name="quantity" id="qty-{{ $item->product_id }}" class="qty-val" value="{{ $item->quantity }}" min="1" max="{{ min($item->product->stock,99) }}" onchange="document.getElementById('form-qty-{{ $item->product_id }}').submit()">
                <button type="button" class="qty-btn" onclick="updateQty({{ $item->product_id }},{{ $item->quantity+1 }})" style="border-radius:0 4px 4px 0">+</button>
              </div>
            </form>
          </div>
          <div style="text-align:right;flex-shrink:0">
            <div style="font-size:15px;font-weight:700;color:var(--orange);margin-bottom:8px">RM {{ number_format($item->product->price * $item->quantity,2) }}</div>
            <form method="POST" action="{{ route('cart.remove',$item->product_id) }}">
              @csrf @method('DELETE')
              <button type="submit" class="btn btn-white btn-sm">🗑 Remove</button>
            </form>
          </div>
        </div>
        @endforeach
      </div>

      {{-- Checkout form --}}
      <div>
        <div class="order-summary">
          <div style="font-size:14px;font-weight:700;margin-bottom:12px;padding-bottom:8px;border-bottom:1px solid var(--border)">Order Summary</div>
          @foreach($items as $item)
          <div class="summary-row">
            <span style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:160px">{{ $item->product->name }} ×{{ $item->quantity }}</span>
            <span>RM {{ number_format($item->product->price * $item->quantity,2) }}</span>
          </div>
          @endforeach
          <div class="summary-row"><span>Subtotal</span><span>RM {{ number_format($total,2) }}</span></div>
          <div class="summary-row"><span>Shipping</span><span style="color:var(--green)">{{ $total >= 50 ? 'FREE' : 'RM 8.00' }}</span></div>
          <div class="summary-row"><span>Total</span><span>RM {{ number_format($total + ($total >= 50 ? 0 : 8),2) }}</span></div>
        </div>

        <div class="card mt-2" style="margin-top:12px">
          <div style="font-size:14px;font-weight:700;margin-bottom:12px">📦 Shipping & Payment</div>
          <form method="POST" action="{{ route('cart.checkout') }}">
            @csrf
            <div class="form-group">
              <label class="form-label" for="address">Delivery Address *</label>
              <textarea name="address" class="form-control" rows="3" required minlength="5" maxlength="500" placeholder="Full delivery address including postcode and state">{{ old('address') }}</textarea>
              @error('address')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
              <label class="form-label" for="phone">Phone Number *</label>
              <input type="text" name="phone" class="form-control" required placeholder="e.g. 0123456789" value="{{ old('phone') }}" maxlength="20">
              @error('phone')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
              <label class="form-label" for="payment">Payment Method *</label>
              <label class="payment-option"><input type="radio" name="payment" value="online_banking" required checked> <span class="payment-label">🏦 Online Banking (FPX)</span></label>
              <label class="payment-option"><input type="radio" name="payment" value="credit_card"> <span class="payment-label">💳 Credit / Debit Card</span></label>
              <label class="payment-option"><input type="radio" name="payment" value="ewallet"> <span class="payment-label">📱 Touch 'n Go / GrabPay</span></label>
              <label class="payment-option"><input type="radio" name="payment" value="cod"> <span class="payment-label">💵 Cash on Delivery</span></label>
              @error('payment')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
              <label class="form-label" for="notes">Notes (optional)</label>
              <textarea name="notes" class="form-control" rows="2" maxlength="500" placeholder="Special instructions for the seller…"></textarea>
            </div>
            <button type="submit" class="btn btn-orange btn-full btn-lg">Place Order — RM {{ number_format($total + ($total >= 50 ? 0 : 8),2) }}</button>
            <p style="font-size:11px;color:var(--muted);text-align:center;margin-top:8px">🔒 Secured by SecureShop · OWASP ASVS Compliant</p>
          </form>
        </div>
      </div>
    </div>
  @endif
</div>
@push('scripts')
<script>
function updateQty(pid, qty) {
  if (qty < 1) { return; }
  document.getElementById('qty-'+pid).value = qty;
  document.getElementById('form-qty-'+pid).submit();
}
</script>
@endpush
@endsection
