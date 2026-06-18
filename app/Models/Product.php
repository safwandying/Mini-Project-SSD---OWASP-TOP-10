<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = ['name','description','price','original_price','stock','category','image_path','is_active','sold_count','rating'];
    protected function casts(): array {
        return ['price'=>'decimal:2','original_price'=>'decimal:2','stock'=>'integer','is_active'=>'boolean','sold_count'=>'integer','rating'=>'decimal:1'];
    }
    public function orderItems() { return $this->hasMany(OrderItem::class); }
    public function cartItems()  { return $this->hasMany(Cart::class); }
    public function scopeActive($q) { return $q->where('is_active',true)->where('stock','>',0); }

    public function getEmojiAttribute(): string {
        return match($this->category) {
            'electronics'  => '💻',
            'phones'       => '📱',
            'fashion'      => '👗',
            'mens'         => '👔',
            'shoes'        => '👟',
            'beauty'       => '💄',
            'health'       => '💊',
            'groceries'    => '🛒',
            'food'         => '☕',
            'home'         => '🏠',
            'furniture'    => '🪑',
            'toys'         => '🧸',
            'sports'       => '⚽',
            'books'        => '📚',
            'automotive'   => '🚗',
            'pets'         => '🐾',
            default        => '📦',
        };
    }

    public function getDiscountAttribute(): int {
        if ($this->original_price && $this->original_price > $this->price) {
            return round((1 - $this->price / $this->original_price) * 100);
        }
        return 0;
    }
}
