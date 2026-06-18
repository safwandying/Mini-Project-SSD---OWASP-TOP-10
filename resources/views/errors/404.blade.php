@extends('layouts.app')
@section('title','Page Not Found')
@section('content')
<div style="text-align:center;padding:80px 20px;max-width:480px;margin:0 auto">
  <div style="font-size:5rem;margin-bottom:16px">🔍</div>
  <h1 style="font-size:3rem;font-weight:700;color:#ee4d2d;margin-bottom:8px">404</h1>
  <h2 style="font-size:16px;font-weight:600;margin-bottom:12px">Page Not Found</h2>
  <p style="color:var(--muted);margin-bottom:24px">The page you're looking for doesn't exist.</p>
  <a href="{{ route('home') }}" class="btn btn-orange">Back to Home</a>
</div>
@endsection
