<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name',200);
            $table->text('description');
            $table->decimal('price',10,2);
            $table->decimal('original_price',10,2)->nullable();
            $table->unsignedInteger('stock')->default(0);
            $table->unsignedInteger('sold_count')->default(0);
            $table->decimal('rating',2,1)->default(4.5);
            $table->enum('category',['electronics','phones','fashion','mens','shoes','beauty','health','groceries','food','home','furniture','toys','sports','books','automotive','pets','other']);
            $table->string('image_path')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->index(['is_active','category']);
        });
    }
    public function down(): void { Schema::dropIfExists('products'); }
};
