@extends('layouts.app')
@section('title','Order #'.$order->id)
@section('content')
<div class="page-wrap" style="max-width:900px;margin:16px auto;padding:0 16px">
  <a href="{{ route('orders.index') }}" style="font-size:13px;color:var(--muted)">← Back to My Orders</a>
  <div style="font-size:16px;font-weight:700;margin:12px 0">Order #{{ $order->id }}</div>

  @php $steps=['pending'=>0,'processing'=>1,'shipped'=>2,'delivered'=>3,'completed'=>4,'cancelled'=>-1]; $cur=$steps[$order->status]??0; @endphp
  @if($order->status !== 'cancelled')
  <div class="card" style="margin-bottom:16px">
    <div style="font-size:13px;font-weight:600;margin-bottom:16px">Order Status</div>
    <div class="status-steps">
      @foreach(['pending'=>'🕐 Pending','processing'=>'📦 Processing','shipped'=>'🚚 Shipped','delivered'=>'🏠 Delivered','completed'=>'✅ Completed'] as $s=>$label)
      @php $idx=$steps[$s]; @endphp
      <div class="status-step">
        <div class="step-dot {{ $cur>=$idx?'done':($cur+1==$idx?'active':'') }}">{{ $cur>=$idx?'✓':$idx+1 }}</div>
        <div class="step-label {{ $cur>=$idx?'active':'' }}">{{ $label }}</div>
      </div>
      @endforeach
    </div>
  </div>
  @else
  <div class="flash flash-error" style="margin-bottom:16px">❌ This order was cancelled.</div>
  @endif

  <div style="display:grid;grid-template-columns:1fr 320px;gap:16px">
    <div>
      <div class="card" style="margin-bottom:12px">
        <div style="font-size:13px;font-weight:600;margin-bottom:12px">Items Ordered</div>
        @foreach($order->items as $item)
        <div style="display:flex;gap:12px;padding:12px 0;border-bottom:1px solid #f0f0f0">
          <div style="width:64px;height:64px;background:#f5f5f5;border-radius:4px;display:flex;align-items:center;justify-content:center;font-size:2rem;flex-shrink:0">{{ $item->product?->emoji??'📦' }}</div>
          <div style="flex:1">
            <div style="font-size:13px;font-weight:500">{{ $item->product?->name??'Deleted product' }}</div>
            <div style="font-size:12px;color:var(--muted);margin-top:4px">Qty: {{ $item->quantity }} × RM {{ number_format($item->unit_price,2) }}</div>
          </div>
          <div style="font-weight:700;color:var(--orange)">RM {{ number_format($item->unit_price*$item->quantity,2) }}</div>
        </div>
        @endforeach
        <div style="display:flex;justify-content:flex-end;padding-top:12px;font-size:15px;font-weight:700">
          Total: <span style="color:var(--orange);margin-left:8px">RM {{ number_format($order->total_amount,2) }}</span>
        </div>
      </div>

      @if($order->notes)
      <div class="card">
        <div style="font-size:13px;font-weight:600;margin-bottom:8px">Notes</div>
        <p style="font-size:13px;color:var(--muted)">{{ $order->notes }}</p>
      </div>
      @endif
    </div>

    <div>
      <div class="card" style="margin-bottom:12px">
        <div style="font-size:13px;font-weight:600;margin-bottom:12px">Delivery Details</div>
        <div style="font-size:13px;line-height:1.8;color:var(--muted)">
          <div>📍 {{ $order->shipping_address ?? '-' }}</div>
          <div>📞 {{ $order->phone ?? '-' }}</div>
          @if($order->tracking_number)<div>📦 Tracking: <strong style="color:var(--text)">{{ $order->tracking_number }}</strong></div>@endif
        </div>
      </div>
      <div class="card">
        <div style="font-size:13px;font-weight:600;margin-bottom:12px">Payment Info</div>
        <div style="font-size:13px;color:var(--muted);line-height:1.8">
          <div>Method: {{ ucwords(str_replace('_',' ',$order->payment_method??'-')) }}</div>
          <div>Date: {{ $order->created_at->format('d M Y, h:i A') }}</div>
          <div style="font-size:15px;font-weight:700;color:var(--orange);margin-top:8px">RM {{ number_format($order->total_amount,2) }}</div>
        </div>
      </div>
      @if(in_array($order->status,['pending','processing']))
      <form method="POST" action="{{ route('orders.cancel',$order->id) }}" style="margin-top:12px">
        @csrf
        <button type="submit" class="btn btn-white btn-full" style="color:#ee4d2d;border-color:#ee4d2d" onclick="return confirm('Cancel this order?')">Cancel Order</button>
      </form>
      @endif
    </div>
  </div>
</div>
@endsection
