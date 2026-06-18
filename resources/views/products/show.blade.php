@extends('layouts.app')
@section('title',$product->name)
@section('content')
<div class="page-wrap">
  {{-- Breadcrumb --}}
  <div style="font-size:12px;color:var(--muted);margin-bottom:12px">
    <a href="{{ route('home') }}" style="color:var(--muted)">Home</a> &rsaquo;
    <a href="{{ route('products.index',['category'=>$product->category]) }}" style="color:var(--muted)">{{ ucfirst($product->category) }}</a> &rsaquo;
    <span style="color:var(--text)">{{ Str::limit($product->name,40) }}</span>
  </div>

  <div class="card" style="display:grid;grid-template-columns:420px 1fr;gap:32px;margin-bottom:16px">
    {{-- Product image --}}
    <div>
      <div style="aspect-ratio:1;background:#f5f5f5;border-radius:4px;display:flex;align-items:center;justify-content:center;font-size:8rem;margin-bottom:12px;position:relative">
        {{ $product->emoji }}
        @if($product->discount > 0)
          <div style="position:absolute;top:12px;left:0;background:var(--orange);color:#fff;font-size:12px;font-weight:700;padding:4px 10px">-{{ $product->discount }}% OFF</div>
        @endif
      </div>
      <div style="display:flex;gap:8px">
        @for($i=0;$i<4;$i++)
        <div style="width:64px;height:64px;background:#f0f0f0;border-radius:4px;display:flex;align-items:center;justify-content:center;font-size:1.5rem;border:1px solid var(--border);cursor:pointer">{{ $product->emoji }}</div>
        @endfor
      </div>
    </div>

    {{-- Product details --}}
    <div>
      <div style="font-size:12px;margin-bottom:8px">
        <span class="badge badge-orange">{{ ucfirst($product->category) }}</span>
        @if($product->stock < 10)<span class="badge badge-red" style="margin-left:6px">Only {{ $product->stock }} left!</span>@endif
      </div>
      <h1 style="font-size:18px;font-weight:600;line-height:1.4;margin-bottom:12px">{{ $product->name }}</h1>

      {{-- Rating & sold --}}
      <div style="display:flex;align-items:center;gap:16px;margin-bottom:16px;padding-bottom:16px;border-bottom:1px solid #f0f0f0;font-size:13px">
        <span style="color:var(--yellow);font-weight:700">★ {{ number_format($product->rating,1) }}</span>
        <span style="color:var(--muted)">{{ number_format($product->sold_count) }} Sold</span>
        <span style="color:var(--muted)">{{ $product->stock }} in stock</span>
      </div>

      {{-- Pricing --}}
      <div style="background:var(--orange-light);padding:16px;border-radius:var(--r);margin-bottom:20px">
        <div style="display:flex;align-items:baseline;gap:12px">
          <span style="font-size:26px;font-weight:700;color:var(--orange)">RM {{ number_format($product->price,2) }}</span>
          @if($product->original_price)
            <span style="font-size:15px;color:var(--muted);text-decoration:line-through">RM {{ number_format($product->original_price,2) }}</span>
            <span style="background:var(--orange);color:#fff;font-size:11px;font-weight:700;padding:2px 7px;border-radius:2px">-{{ $product->discount }}%</span>
          @endif
        </div>
        @if($product->original_price)
          <div style="font-size:12px;color:var(--orange);margin-top:4px">You save RM {{ number_format($product->original_price - $product->price, 2) }}</div>
        @endif
      </div>

      {{-- Shipping --}}
      <div style="font-size:13px;color:var(--muted);margin-bottom:16px;display:flex;flex-direction:column;gap:6px">
        <div>🚚 <strong>Free Shipping</strong> on orders above RM50</div>
        <div>📦 Ships from Selangor · Estimated 2-5 business days</div>
        <div>🔄 7-day hassle-free returns</div>
      </div>

      {{-- Add to cart --}}
      @auth
        @if($product->stock > 0)
          <form method="POST" action="{{ route('cart.add',$product) }}">
            @csrf
            <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px">
              <span style="font-size:13px;font-weight:500">Quantity:</span>
              <div style="display:flex;align-items:center">
                <div class="qty-ctrl">
                  <button type="button" class="qty-btn" onclick="let v=document.getElementById('qty');v.value=Math.max(1,+v.value-1)" style="border-radius:4px 0 0 4px">−</button>
                  <input type="number" name="quantity" id="qty" class="qty-val" value="1" min="1" max="{{ min($product->stock,99) }}">
                  <button type="button" class="qty-btn" onclick="let v=document.getElementById('qty');v.value=Math.min({{ min($product->stock,99) }},+v.value+1)" style="border-radius:0 4px 4px 0">+</button>
                </div>
              </div>
            </div>
            <div style="display:flex;gap:12px">
              <button type="submit" class="btn btn-outline-orange btn-lg">🛒 Add to Cart</button>
              <button type="submit" name="buy_now" value="1" class="btn btn-orange btn-lg">⚡ Buy Now</button>
            </div>
          </form>
        @else
          <div style="background:#f5f5f5;padding:16px;border-radius:var(--r);text-align:center;color:var(--muted)">
            <div style="font-size:2rem;margin-bottom:8px">😔</div>
            <div style="font-weight:600">Out of Stock</div>
            <div style="font-size:12px;margin-top:4px">This item is currently unavailable</div>
          </div>
        @endif
      @else
        <div style="margin-top:16px">
          <a href="{{ route('login') }}" class="btn btn-orange btn-lg btn-full">Login to Purchase</a>
          <p style="text-align:center;font-size:12px;color:var(--muted);margin-top:8px">New customer? <a href="{{ route('register') }}" style="color:var(--orange)">Register here</a></p>
        </div>
      @endauth

      {{-- Security note --}}
      <div class="sec-note mt-2">
        🔒 Protected checkout · bcrypt encryption · CSRF protection · No card details stored
      </div>
    </div>
  </div>

  {{-- Description --}}
  <div class="card">
    <div style="font-size:15px;font-weight:700;margin-bottom:12px;color:var(--orange);border-bottom:2px solid var(--orange);padding-bottom:8px">Product Description</div>
    <p style="font-size:14px;line-height:1.8;color:var(--text)">{{ $product->description }}</p>
  </div>
</div>
@endsection
