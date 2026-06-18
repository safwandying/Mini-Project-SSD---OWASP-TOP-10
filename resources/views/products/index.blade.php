@extends('layouts.app')
@section('title','SecureShop — Malaysia\'s Secure Online Store')
@section('content')
<div class="page-wrap">
  {{-- Hero --}}
  @if(!request('search') && !request('category'))
  <div class="hero-banner">
    <div class="hero-title">🛡️ Shop Securely, Shop Smart</div>
    <div class="hero-sub">OWASP-secured platform · Best deals every day · Free shipping above RM50</div>
    <a href="{{ route('products.index',['category'=>'electronics']) }}" class="btn btn-white">Shop Electronics →</a>
  </div>
  @endif

  {{-- Search result header --}}
  @if(request('search') || request('category'))
  <div class="card mb-2" style="margin-bottom:12px">
    <div class="flex items-center justify-between">
      <span style="font-size:13px;color:var(--muted)">
        @if(request('search'))Results for "<strong style="color:var(--text)">{{ request('search') }}</strong>"@endif
        @if(request('category')) in <strong style="color:var(--orange)">{{ ucfirst(request('category')) }}</strong>@endif
        — <strong>{{ $products->total() }}</strong> items found
      </span>
      <a href="{{ route('products.index') }}" style="font-size:12px;color:var(--orange)">✕ Clear filters</a>
    </div>
  </div>
  @endif

  {{-- Section header --}}
  <div class="section-header">
    <div class="section-title">
      {{ request('category') ? ucfirst(request('category')) : (request('search') ? 'Search Results' : '🔥 Daily Deals') }}
    </div>
    <span class="text-muted text-sm">{{ $products->total() }} products</span>
  </div>

  @if($products->isEmpty())
    <div class="card" style="text-align:center;padding:60px 20px">
      <div style="font-size:4rem;margin-bottom:12px">🔍</div>
      <p style="color:var(--muted);margin-bottom:16px">No products found.</p>
      <a href="{{ route('products.index') }}" class="btn btn-orange">Back to Home</a>
    </div>
  @else
    <div class="product-grid">
      @foreach($products as $product)
      <a href="{{ route('products.show',$product) }}" class="product-card" style="text-decoration:none;color:inherit">
        <div class="product-thumb">
          <span class="emoji-bg">{{ $product->emoji }}</span>
          <span class="emoji-main">{{ $product->emoji }}</span>
          @if($product->discount > 0)
            <div class="discount-tag">-{{ $product->discount }}%</div>
          @endif
        </div>
        <div class="product-info">
          <div class="product-name">{{ $product->name }}</div>
          <div class="product-price-row">
            <span class="price-now">RM {{ number_format($product->price,2) }}</span>
            @if($product->original_price)
              <span class="price-was">RM {{ number_format($product->original_price,2) }}</span>
            @endif
          </div>
          <div class="product-meta">
            <span class="sold-text">{{ number_format($product->sold_count) }} sold</span>
            <span class="rating-stars">★ {{ number_format($product->rating,1) }}</span>
          </div>
        </div>
      </a>
      @endforeach
    </div>
    <div class="pagination mt-3">{{ $products->withQueryString()->links() }}</div>
  @endif
</div>
@endsection
