<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title','SecureShop') — Malaysia's Secure Online Store</title>
<link rel="stylesheet" href="{{ asset('css/fonts.css') }}">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{--orange:#ee4d2d;--orange-dark:#d73211;--orange-light:#fff2ee;--blue:#1a94ff;--green:#26aa99;--yellow:#ffd600;--bg:#f5f5f5;--white:#fff;--border:#e0e0e0;--text:#333;--muted:#757575;--muted2:#bdbdbd;--r:4px;--shadow:0 1px 4px rgba(0,0,0,.12)}
body{font-family:'Inter',sans-serif;background:var(--bg);color:var(--text);font-size:14px;line-height:1.5}
a{color:var(--text);text-decoration:none}
img{max-width:100%}
::-webkit-scrollbar{width:5px}::-webkit-scrollbar-track{background:#f1f1f1}::-webkit-scrollbar-thumb{background:var(--muted2)}

/* ── TOP BAR ── */
.topbar{background:var(--orange-dark);color:#fff;font-size:12px;padding:4px 0}
.topbar-inner{max-width:1200px;margin:0 auto;padding:0 16px;display:flex;justify-content:space-between;align-items:center}
.topbar a{color:#fff;opacity:.85}.topbar a:hover{opacity:1}
.topbar-links{display:flex;gap:16px;align-items:center}

/* ── HEADER ── */
.header{background:var(--orange);padding:10px 0;position:sticky;top:0;z-index:200}
.header-inner{max-width:1200px;margin:0 auto;padding:0 16px;display:flex;align-items:center;gap:16px}
.logo{font-size:1.6rem;font-weight:700;color:#fff;letter-spacing:-1px;white-space:nowrap;flex-shrink:0}
.logo span{background:#fff;color:var(--orange);padding:0 4px;border-radius:2px}

/* Search bar */
.search-wrap{flex:1;display:flex;max-width:640px}
.search-input{flex:1;padding:10px 14px;border:none;outline:none;font-size:14px;font-family:'Inter',sans-serif;border-radius:var(--r) 0 0 var(--r)}
.search-btn{background:#f05c3a;border:none;padding:0 20px;cursor:pointer;border-radius:0 var(--r) var(--r) 0;color:#fff;font-size:13px;font-weight:600;transition:background .2s}
.search-btn:hover{background:var(--orange-dark)}

/* Header actions */
.header-actions{display:flex;align-items:center;gap:8px;flex-shrink:0}
.header-btn{color:#fff;display:flex;flex-direction:column;align-items:center;gap:2px;font-size:11px;padding:4px 8px;border-radius:var(--r);cursor:pointer;border:none;background:none;font-family:'Inter',sans-serif;transition:background .2s;position:relative}
.header-btn:hover{background:rgba(255,255,255,.15)}
.header-btn .icon{font-size:22px;line-height:1}
.cart-badge{position:absolute;top:0;right:4px;background:#fff;color:var(--orange);border-radius:50%;width:18px;height:18px;font-size:10px;font-weight:700;display:flex;align-items:center;justify-content:center}

/* ── CATEGORY NAV ── */
.cat-nav{background:#fff;border-bottom:1px solid var(--border);overflow-x:auto}
.cat-nav-inner{max-width:1200px;margin:0 auto;padding:0 16px;display:flex;gap:0;white-space:nowrap}
.cat-link{display:inline-flex;align-items:center;gap:6px;padding:10px 14px;font-size:13px;color:var(--muted);border-bottom:2px solid transparent;transition:all .2s;cursor:pointer;border-top:none;border-left:none;border-right:none;background:none;font-family:'Inter',sans-serif}
.cat-link:hover,.cat-link.active{color:var(--orange);border-bottom-color:var(--orange)}

/* ── LAYOUT ── */
.page-wrap{max-width:1200px;margin:0 auto;padding:16px}

/* ── FLASH ── */
.flash{padding:10px 16px;border-radius:var(--r);margin-bottom:12px;font-size:13px;display:flex;align-items:center;gap:8px}
.flash-success{background:#f0fff4;border:1px solid #c6f6d5;color:#276749}
.flash-error{background:#fff5f5;border:1px solid #fed7d7;color:#c53030}

/* ── CARDS ── */
.card{background:var(--white);border-radius:var(--r);padding:16px;box-shadow:var(--shadow)}

/* ── PRODUCT GRID ── */
.product-grid{display:grid;grid-template-columns:repeat(6,1fr);gap:8px}
@media(max-width:1100px){.product-grid{grid-template-columns:repeat(5,1fr)}}
@media(max-width:900px){.product-grid{grid-template-columns:repeat(4,1fr)}}
@media(max-width:700px){.product-grid{grid-template-columns:repeat(3,1fr)}}
@media(max-width:500px){.product-grid{grid-template-columns:repeat(2,1fr)}}
.product-card{background:var(--white);border-radius:var(--r);overflow:hidden;transition:box-shadow .2s;cursor:pointer;display:flex;flex-direction:column}
.product-card:hover{box-shadow:0 4px 20px rgba(0,0,0,.15)}
.product-thumb{aspect-ratio:1;background:linear-gradient(135deg,#f5f5f5,#e8e8e8);display:flex;align-items:center;justify-content:center;font-size:3rem;position:relative;overflow:hidden}
.product-thumb .emoji-bg{font-size:4rem;opacity:.15;position:absolute;transform:scale(2)}
.product-thumb .emoji-main{font-size:2.8rem;position:relative;z-index:1}
.discount-tag{position:absolute;top:8px;left:0;background:var(--orange);color:#fff;font-size:10px;font-weight:700;padding:2px 6px}
.product-info{padding:8px 8px 12px;flex:1;display:flex;flex-direction:column}
.product-name{font-size:13px;line-height:1.4;margin-bottom:6px;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;color:var(--text)}
.product-price-row{display:flex;align-items:center;gap:6px;flex-wrap:wrap;margin-bottom:4px}
.price-now{font-size:15px;font-weight:600;color:var(--orange)}
.price-was{font-size:11px;color:var(--muted);text-decoration:line-through}
.product-meta{display:flex;align-items:center;justify-content:space-between;margin-top:auto}
.sold-text{font-size:11px;color:var(--muted)}
.rating-stars{font-size:11px;color:var(--yellow)}

/* ── BUTTONS ── */
.btn{display:inline-flex;align-items:center;justify-content:center;gap:6px;padding:8px 20px;border-radius:var(--r);font-size:13px;font-weight:500;cursor:pointer;border:none;transition:all .2s;font-family:'Inter',sans-serif;white-space:nowrap}
.btn:active{opacity:.85}
.btn-orange{background:var(--orange);color:#fff}.btn-orange:hover{background:var(--orange-dark)}
.btn-outline-orange{background:#fff;color:var(--orange);border:1px solid var(--orange)}.btn-outline-orange:hover{background:var(--orange-light)}
.btn-white{background:#fff;color:var(--text);border:1px solid var(--border)}.btn-white:hover{background:#f5f5f5}
.btn-green{background:var(--green);color:#fff}.btn-green:hover{opacity:.9}
.btn-sm{padding:6px 14px;font-size:12px}
.btn-lg{padding:12px 28px;font-size:15px}
.btn-full{width:100%}
.btn-danger{background:#ee4d2d;color:#fff}

/* ── FORMS ── */
.form-group{margin-bottom:14px}
.form-label{display:block;font-size:13px;font-weight:500;margin-bottom:5px;color:var(--text)}
.form-control{width:100%;padding:9px 12px;border:1px solid var(--border);border-radius:var(--r);font-size:13px;font-family:'Inter',sans-serif;color:var(--text);transition:border-color .2s;background:#fff}
.form-control:focus{outline:none;border-color:var(--orange);box-shadow:0 0 0 2px rgba(238,77,45,.1)}
.form-error{font-size:12px;color:#ee4d2d;margin-top:4px}
.form-hint{font-size:12px;color:var(--muted);margin-top:4px}
select.form-control{cursor:pointer}

/* ── TAGS / BADGES ── */
.badge{display:inline-flex;align-items:center;padding:2px 8px;border-radius:20px;font-size:11px;font-weight:600}
.badge-orange{background:var(--orange-light);color:var(--orange)}
.badge-green{background:#e6f9f5;color:var(--green)}
.badge-blue{background:#e8f4ff;color:var(--blue)}
.badge-gray{background:#f5f5f5;color:var(--muted)}
.badge-red{background:#fff5f5;color:#c53030}
.badge-yellow{background:#fffbea;color:#b7791f}

/* ── TABLE ── */
.table{width:100%;border-collapse:collapse;font-size:13px}
.table th{padding:10px 12px;text-align:left;border-bottom:2px solid var(--border);color:var(--muted);font-weight:500;font-size:12px;text-transform:uppercase;letter-spacing:.5px}
.table td{padding:12px;border-bottom:1px solid #f0f0f0}
.table tr:hover td{background:#fafafa}

/* ── ORDER STATUS STEPS ── */
.status-steps{display:flex;align-items:center;gap:0;margin:16px 0}
.status-step{flex:1;text-align:center;position:relative}
.status-step::before{content:'';position:absolute;top:14px;left:50%;right:-50%;height:2px;background:var(--border);z-index:0}
.status-step:last-child::before{display:none}
.step-dot{width:28px;height:28px;border-radius:50%;border:2px solid var(--border);background:#fff;display:flex;align-items:center;justify-content:center;font-size:12px;margin:0 auto 6px;position:relative;z-index:1;font-weight:700}
.step-dot.done{background:var(--orange);border-color:var(--orange);color:#fff}
.step-dot.active{border-color:var(--orange);color:var(--orange)}
.step-label{font-size:11px;color:var(--muted)}
.step-label.active{color:var(--orange);font-weight:600}

/* ── SIDEBAR ADMIN ── */
.admin-wrap{display:grid;grid-template-columns:200px 1fr;gap:16px;max-width:1200px;margin:16px auto;padding:0 16px}
.admin-sidebar{background:var(--white);border-radius:var(--r);padding:12px;box-shadow:var(--shadow);height:fit-content}
.sidebar-user{padding:12px;border-bottom:1px solid var(--border);margin-bottom:8px;text-align:center}
.sidebar-avatar{width:48px;height:48px;border-radius:50%;background:var(--orange);color:#fff;display:flex;align-items:center;justify-content:center;font-size:1.2rem;font-weight:700;margin:0 auto 8px}
.sidebar-name{font-weight:600;font-size:13px}
.sidebar-role{font-size:11px;color:var(--muted)}
.sidebar-link{display:flex;align-items:center;gap:8px;padding:9px 12px;border-radius:var(--r);color:var(--text);font-size:13px;transition:all .2s;cursor:pointer;border:none;background:none;font-family:'Inter',sans-serif;width:100%;text-align:left;text-decoration:none}
.sidebar-link:hover{background:#f5f5f5;color:var(--orange)}
.sidebar-link.active{background:var(--orange-light);color:var(--orange);font-weight:600}

/* ── HERO BANNER ── */
.hero-banner{background:linear-gradient(135deg,var(--orange) 0%,var(--orange-dark) 100%);border-radius:var(--r);padding:32px;color:#fff;margin-bottom:16px;position:relative;overflow:hidden}
.hero-banner::after{content:'🛍️';position:absolute;right:32px;top:50%;transform:translateY(-50%);font-size:6rem;opacity:.2}
.hero-title{font-size:1.8rem;font-weight:700;margin-bottom:8px;letter-spacing:-.5px}
.hero-sub{font-size:14px;opacity:.9;margin-bottom:20px}

/* ── SECTION HEADER ── */
.section-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;padding-bottom:8px;border-bottom:2px solid var(--orange)}
.section-title{font-size:16px;font-weight:700;color:var(--orange);display:flex;align-items:center;gap:8px}
.section-more{font-size:12px;color:var(--orange)}

/* ── CART ── */
.cart-item{background:var(--white);border-radius:var(--r);padding:16px;margin-bottom:8px;display:flex;align-items:center;gap:12px;box-shadow:var(--shadow)}
.cart-thumb{width:80px;height:80px;background:#f5f5f5;border-radius:var(--r);display:flex;align-items:center;justify-content:center;font-size:2rem;flex-shrink:0}
.cart-details{flex:1;min-width:0}
.cart-name{font-size:13px;font-weight:500;margin-bottom:4px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.cart-price{font-size:15px;font-weight:700;color:var(--orange)}
.qty-ctrl{display:flex;align-items:center;gap:0;margin-top:8px}
.qty-btn{width:28px;height:28px;border:1px solid var(--border);background:#fff;cursor:pointer;font-size:15px;display:flex;align-items:center;justify-content:center;transition:background .2s}
.qty-btn:hover{background:#f5f5f5}
.qty-val{width:40px;height:28px;border-top:1px solid var(--border);border-bottom:1px solid var(--border);text-align:center;font-size:13px;font-family:'Inter',sans-serif;border-left:none;border-right:none;outline:none}

/* ── CHECKOUT ── */
.checkout-wrap{display:grid;grid-template-columns:1fr 340px;gap:16px;align-items:start}
@media(max-width:800px){.checkout-wrap{grid-template-columns:1fr}}
.order-summary{background:var(--white);border-radius:var(--r);padding:16px;box-shadow:var(--shadow);position:sticky;top:80px}
.summary-row{display:flex;justify-content:space-between;align-items:center;padding:8px 0;font-size:13px;border-bottom:1px solid #f0f0f0}
.summary-row:last-child{border-bottom:none;font-size:15px;font-weight:700;color:var(--orange);padding-top:12px}

/* ── PAYMENT OPTIONS ── */
.payment-option{display:flex;align-items:center;gap:10px;padding:12px;border:1px solid var(--border);border-radius:var(--r);cursor:pointer;transition:all .2s;margin-bottom:8px}
.payment-option:has(input:checked){border-color:var(--orange);background:var(--orange-light)}
.payment-option input{accent-color:var(--orange)}
.payment-label{display:flex;align-items:center;gap:8px;font-size:13px;cursor:pointer;flex:1}

/* ── PAGINATION ── */
/* Pagination — works with Laravel default pagination */
nav[role=navigation]{display:flex;justify-content:center;margin-top:20px}
nav[role=navigation] > div:first-child{display:none}
nav[role=navigation] span[aria-current] span,
nav[role=navigation] a,
nav[role=navigation] span{display:inline-flex;align-items:center;justify-content:center;min-width:36px;height:36px;padding:0 8px;border:1px solid var(--border);border-radius:var(--r);font-size:13px;color:var(--text);background:#fff;margin:0 2px;text-decoration:none;transition:all .2s}
nav[role=navigation] a:hover{border-color:var(--orange);color:var(--orange)}
nav[role=navigation] span[aria-current] span{background:var(--orange);border-color:var(--orange);color:#fff;font-weight:600}
nav[role=navigation] span.cursor-default{color:var(--muted2);background:#fafafa}
/* Hide SVG arrows, show text instead */
nav[role=navigation] svg{display:none}
nav[role=navigation] a[rel=prev]::before{content:"‹ Prev"}
nav[role=navigation] a[rel=next]::after{content:"Next ›"}

/* ── FOOTER ── */
.footer{background:#333;color:#ccc;padding:32px 0;margin-top:32px;font-size:13px}
.footer-inner{max-width:1200px;margin:0 auto;padding:0 16px;display:grid;grid-template-columns:repeat(4,1fr);gap:24px}
.footer h4{color:#fff;font-size:14px;font-weight:600;margin-bottom:12px}
.footer a{color:#bbb;display:block;margin-bottom:6px}.footer a:hover{color:#fff}
.footer-bottom{border-top:1px solid #555;margin-top:24px;padding-top:16px;text-align:center;color:#888;font-size:12px}

/* ── MISC ── */
.divider{height:1px;background:var(--border);margin:16px 0}
.text-orange{color:var(--orange)}
.text-muted{color:var(--muted)}
.text-sm{font-size:12px}
.mt-1{margin-top:8px}.mt-2{margin-top:16px}.mt-3{margin-top:24px}
.mb-1{margin-bottom:8px}.mb-2{margin-bottom:16px}
.flex{display:flex}.items-center{align-items:center}.justify-between{justify-content:space-between}.gap-1{gap:8px}.gap-2{gap:16px}.flex-wrap{flex-wrap:wrap}
.grid-2{display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:16px}
.sec-note{background:#fff7f5;border:1px solid #ffd4c8;border-radius:var(--r);padding:10px 14px;font-size:12px;color:var(--orange)}
</style>
@stack('styles')
</head>
<body>



{{-- MAIN HEADER --}}
<div class="header">
  <div class="header-inner">
    <a href="{{ route('home') }}" class="logo">Secure<span>Shop</span></a>

    <form method="GET" action="{{ route('products.index') }}" class="search-wrap">
      <input type="text" name="search" class="search-input" placeholder="Search for products, brands and more…" value="{{ request('search') }}" maxlength="100">
      <button type="submit" class="search-btn">🔍 Search</button>
    </form>

    <div class="header-actions">
      @php $cartCount = 0;
      if(auth()->check()) {
          $cartCount = \App\Models\Cart::where('user_id',auth()->id())->sum('quantity');
      } @endphp

      @auth
        <a href="{{ route('orders.index') }}" class="header-btn">
          <span class="icon">📦</span>
          <span>Orders</span>
        </a>
        <a href="{{ route('profile.show') }}" class="header-btn">
          <span class="icon">👤</span>
          <span>{{ Auth::user()->name }}</span>
        </a>
        @if(Auth::user()->isAdmin())
          <a href="{{ route('admin.dashboard') }}" class="header-btn" style="color:#ffd600">
            <span class="icon">⚡</span>
            <span>Admin</span>
          </a>
        @endif
        <form method="POST" action="{{ route('logout') }}" style="display:inline">
          @csrf
          <button type="submit" class="header-btn">
            <span class="icon">🚪</span>
            <span>Logout</span>
          </button>
        </form>
      @else
        <a href="{{ route('login') }}" class="header-btn">
          <span class="icon">👤</span>
          <span>Login</span>
        </a>
        <a href="{{ route('register') }}" class="header-btn" style="background:rgba(255,255,255,.2);border-radius:4px">
          <span class="icon">✍️</span>
          <span>Register</span>
        </a>
      @endauth

      <a href="{{ route('cart.index') }}" class="header-btn">
        <span class="icon">🛒</span>
        <span>Cart</span>
        @if($cartCount > 0)<span class="cart-badge">{{ $cartCount }}</span>@endif
      </a>
    </div>
  </div>
</div>

{{-- CATEGORY NAV --}}
<div class="cat-nav">
  <div class="cat-nav-inner">
    @php
    $cats = [
      ''             => '🏠 All',
      'electronics'  => '💻 Electronics',
      'phones'       => '📱 Phones',
      'fashion'      => '👗 Women Fashion',
      'mens'         => '👔 Men Fashion',
      'shoes'        => '👟 Shoes',
      'beauty'       => '💄 Beauty',
      'health'       => '💊 Health',
      'groceries'    => '🛒 Groceries',
      'home'         => '🏠 Home & Living',
      'furniture'    => '🪑 Furniture',
      'toys'         => '🧸 Toys',
      'sports'       => '⚽ Sports',
      'books'        => '📚 Books',
      'automotive'   => '🚗 Automotive',
      'pets'         => '🐾 Pets',
    ];
    @endphp
    @foreach($cats as $val=>$label)
      <a href="{{ route('products.index',array_merge(request()->except('page'),['category'=>$val])) }}"
         class="cat-link {{ request('category')==$val ? 'active' : '' }}">{{ $label }}</a>
    @endforeach
  </div>
</div>

{{-- FLASH MESSAGES --}}
@if(session('success') || session('error') || $errors->any())
<div style="max-width:1200px;margin:12px auto;padding:0 16px">
  @if(session('success'))<div class="flash flash-success">✓ {{ session('success') }}</div>@endif
  @if(session('error'))<div class="flash flash-error">✗ {{ session('error') }}</div>@endif
  @if($errors->any())
    <div class="flash flash-error">
      @foreach($errors->all() as $e)<div>✗ {{ $e }}</div>@endforeach
    </div>
  @endif
</div>
@endif

@yield('content')

{{-- FOOTER --}}
<div class="footer">
  <div class="footer-inner">
    <div>
      <h4>SecureShop</h4>
      <a href="#">About Us</a>
      <a href="#">Careers</a>
      <a href="#">Blog</a>
      <a href="#">Privacy Policy</a>
    </div>
    <div>
      <h4>Customer Service</h4>
      <a href="#">Help Centre</a>
      <a href="#">How to Buy</a>
      <a href="#">Returns & Refunds</a>
      <a href="#">Track Order</a>
    </div>
    <div>
      <h4>Payment</h4>
      <a href="#">Online Banking (FPX)</a>
      <a href="#">Credit / Debit Card</a>
      <a href="#">Touch 'n Go eWallet</a>
      <a href="#">Cash on Delivery</a>
    </div>
    <div>
      <h4>Follow Us</h4>
      <a href="#">Facebook</a>
      <a href="#">Instagram</a>
      <a href="#">TikTok</a>
      <p style="margin-top:12px;font-size:11px;color:#888">🔒 OWASP Top 10 Secured<br>Laravel 11 · MySQL · IKB21503</p>
    </div>
  </div>
  <div class="footer-bottom">© {{ date('Y') }} SecureShop — IKB21503 Secure Software Development · UniKL MIIT</div>
</div>

@stack('scripts')
</body>
</html>
