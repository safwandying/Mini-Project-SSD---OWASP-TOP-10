<?php
namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\AuditLog;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/** OWASP ASVS V5,V12 - Input Validation & File Upload | SSDF PW.5,PW.7 */
class ProductController extends Controller
{
    // Public shop listing
    public function index(Request $request)
    {
        $query = Product::active();
        if ($s = $request->input('search')) {
            $safe = substr(strip_tags($s), 0, 100);
            $query->where(fn($q) => $q->where('name','like',"%$safe%")->orWhere('description','like',"%$safe%"));
        }
        if ($c = $request->input('category')) {
            $allowed = ['electronics','phones','fashion','mens','shoes','beauty','health','groceries','food','home','furniture','toys','sports','books','automotive','pets','other'];
            if (in_array($c, $allowed)) { $query->where('category', $c); }
        }
        $products = $query->orderBy('created_at','desc')->paginate(12);
        return view('products.index', compact('products'));
    }

    public function show(Product $product)
    {
        if (!$product->is_active && !optional(Auth::user())->isAdmin()) { abort(404); }
        return view('products.show', compact('product'));
    }

    // Admin CRUD
    public function adminIndex()
    {
        $products = Product::orderBy('created_at','desc')->paginate(20);
        return view('admin.products.index', compact('products'));
    }

    public function create() { return view('admin.products.create'); }

    public function store(ProductRequest $request)
    {
        $data = $request->validated();
        if ($request->hasFile('image')) { $data['image_path'] = $this->storeImage($request->file('image')); }
        $product = Product::create($data);
        AuditLog::record('product_created', "Admin created product [{$product->id}]: {$product->name}", Auth::id());
        return redirect()->route('admin.products.index')->with('success', "Product \"{$product->name}\" created.");
    }

    public function edit(Product $product) { return view('admin.products.edit', compact('product')); }

    public function update(ProductRequest $request, Product $product)
    {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            if ($product->image_path) { Storage::disk('local')->delete("products/{$product->image_path}"); }
            $data['image_path'] = $this->storeImage($request->file('image'));
        }
        $product->update($data);
        AuditLog::record('product_updated', "Admin updated product [{$product->id}]: {$product->name}", Auth::id());
        return redirect()->route('admin.products.index')->with('success', "Product updated.");
    }

    public function destroy(Product $product)
    {
        if ($product->image_path) { Storage::disk('local')->delete("products/{$product->image_path}"); }
        $name = $product->name; $id = $product->id;
        $product->delete();
        AuditLog::record('product_deleted', "Admin deleted product [{$id}]: {$name}", Auth::id());
        return redirect()->route('admin.products.index')->with('success', "Product deleted.");
    }

    private function storeImage($file): string
    {
        $filename = Str::uuid().'.'.$file->extension(); // UUID - prevents path traversal
        $file->storeAs('products', $filename, 'local'); // outside webroot
        return $filename;
    }
}
