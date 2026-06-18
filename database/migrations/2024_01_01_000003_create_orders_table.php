<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('total_amount',10,2);
            $table->enum('status',['pending','processing','shipped','delivered','completed','cancelled'])->default('pending');
            $table->text('shipping_address')->nullable();
            $table->string('phone',20)->nullable();
            $table->string('payment_method',30)->nullable();
            $table->string('tracking_number',60)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index(['user_id','status']);
        });
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->unsignedInteger('quantity');
            $table->decimal('unit_price',10,2);
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};
