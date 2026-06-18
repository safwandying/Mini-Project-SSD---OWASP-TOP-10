@extends('layouts.app')
@section('title', isset($product) ? 'Edit Product' : 'Add Product')
@section('content')
<div class="admin-wrap">
  <aside class="admin-sidebar">
    <div class="sidebar-user">
      <div class="sidebar-avatar">{{ Auth::user()->name[0] }}</div>
      <div class="sidebar-name">{{ Auth::user()->name }}</div>
      <div class="sidebar-role" style="color:var(--orange);font-weight:600">Administrator</div>
    </div>
    <a href="{{ route('admin.dashboard') }}"      class="sidebar-link">📊 Dashboard</a>
    <a href="{{ route('admin.products.index') }}" class="sidebar-link active">📦 Products</a>
    <a href="{{ route('admin.orders') }}"         class="sidebar-link">📋 Orders</a>
    <a href="{{ route('admin.users') }}"          class="sidebar-link">👥 Users</a>
    <a href="{{ route('admin.audit-logs') }}"     class="sidebar-link">🔍 Audit Logs</a>
    <div style="border-top:1px solid var(--border);margin:8px 0;padding-top:8px">
      <a href="{{ route('products.index') }}" class="sidebar-link">🏠 View Store</a>
    </div>
  </aside>

  <div>
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px">
      <div style="font-size:16px;font-weight:700">{{ isset($product) ? '✏️ Edit Product' : '➕ Add New Product' }}</div>
      <a href="{{ route('admin.products.index') }}" class="btn btn-white btn-sm">← Back</a>
    </div>

    <div class="card" style="max-width:700px">
      <form method="POST"
        action="{{ isset($product) ? route('admin.products.update',$product) : route('admin.products.store') }}"
        enctype="multipart/form-data">
        @csrf
        @if(isset($product)) @method('PUT') @endif

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
          <div class="form-group" style="grid-column:1/-1">
            <label class="form-label" for="name">Product Name *</label>
            <input type="text" name="name" class="form-control" value="{{ old('name',$product->name??'') }}" required minlength="2" maxlength="200" placeholder="e.g. Samsung 65 inch 4K Smart TV">
            @error('name')<div class="form-error">{{ $message }}</div>@enderror
          </div>

          <div class="form-group" style="grid-column:1/-1">
            <label class="form-label" for="description">Description *</label>
            <textarea name="description" class="form-control" rows="4" required minlength="10" maxlength="2000" placeholder="Full product description…">{{ old('description',$product->description??'') }}</textarea>
            @error('description')<div class="form-error">{{ $message }}</div>@enderror
          </div>

          <div class="form-group">
            <label class="form-label" for="price">Sale Price (RM) *</label>
            <input type="number" name="price" class="form-control" value="{{ old('price',$product->price??'') }}" required step="0.01" min="0.01" max="99999.99" placeholder="e.g. 299.00">
            @error('price')<div class="form-error">{{ $message }}</div>@enderror
          </div>

          <div class="form-group">
            <label class="form-label" for="original_price">Original Price (RM) <span style="color:var(--muted);font-weight:400">(optional)</span></label>
            <input type="number" name="original_price" class="form-control" value="{{ old('original_price',$product->original_price??'') }}" step="0.01" min="0" placeholder="e.g. 399.00 (for discount display)">
            @error('original_price')<div class="form-error">{{ $message }}</div>@enderror
          </div>

          <div class="form-group">
            <label class="form-label" for="stock">Stock Quantity *</label>
            <input type="number" name="stock" class="form-control" value="{{ old('stock',$product->stock??0) }}" required min="0" max="100000">
            @error('stock')<div class="form-error">{{ $message }}</div>@enderror
          </div>

          <div class="form-group">
            <label class="form-label" for="rating">Rating (1.0–5.0)</label>
            <input type="number" name="rating" class="form-control" value="{{ old('rating',$product->rating??4.5) }}" min="1.0" max="5.0" step="0.1">
            @error('rating')<div class="form-error">{{ $message }}</div>@enderror
          </div>

          <div class="form-group">
            <label class="form-label" for="category">Category *</label>
            <select name="category" class="form-control" required>
              @foreach(['electronics','phones','fashion','mens','shoes','beauty','health','groceries','food','home','furniture','toys','sports','books','automotive','pets','other'] as $c)
                <option value="{{ $c }}" @selected(old('category',$product->category??'')===$c)>{{ ucfirst($c) }}</option>
              @endforeach
            </select>
            @error('category')<div class="form-error">{{ $message }}</div>@enderror
          </div>

          <div class="form-group">
            <label class="form-label" for="sold_count">Sold Count <span style="color:var(--muted);font-weight:400">(display only)</span></label>
            <input type="number" name="sold_count" class="form-control" value="{{ old('sold_count',$product->sold_count??0) }}" min="0">
          </div>

          <div class="form-group" style="grid-column:1/-1">
            <label class="form-label" for="image">Product Image @if(isset($product)&&$product->image_path)<span style="color:var(--muted);font-weight:400">(leave blank to keep current)</span>@endif</label>
            <input type="file" name="image" class="form-control" accept=".jpg,.jpeg,.png,.webp" {{ !isset($product)?'required':'' }}>
            <div class="form-hint">JPG, PNG or WEBP · Max 2MB · Stored securely outside webroot with UUID filename</div>
            @error('image')<div class="form-error">{{ $message }}</div>@enderror
          </div>

          <div class="form-group" style="grid-column:1/-1;display:flex;align-items:center;gap:8px">
            <input type="checkbox" name="is_active" id="is_active" value="1" @checked(old('is_active',$product->is_active??true))>
            <label for="is_active" for="is_active" style="font-size:13px;cursor:pointer">Active (visible to customers)</label>
          </div>
        </div>

        <div style="display:flex;gap:8px;margin-top:8px">
          <button type="submit" class="btn btn-orange">{{ isset($product)?'Update Product':'Create Product' }}</button>
          <a href="{{ route('admin.products.index') }}" class="btn btn-white">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
