<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_product', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->nullable()->references('id')->on('products')->nullOnDelete();
            $table->foreignId('order_id')->nullable()->references('id')->on('order')->nullOnDelete();
            $table->decimal('item_price', 8, 2); // item_price field
            $table->integer('quantity')->unsigned()->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_product');
    }
};
