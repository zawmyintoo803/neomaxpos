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
        Schema::create('products', function (Blueprint $table) {
            $table->id(); // primary key
            $table->string('product_code')->unique();
            $table->string('product_name');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('unit_id')->nullable();
            $table->integer('stock')->default(0);
            $table->date('expiry_date')->nullable();
            $table->string('image')->nullable();
            $table->decimal('price', 12, 2)->default(0); // optional for POS
            $table->decimal('discount', 12, 2)->default(0); // optional
            $table->timestamps();

        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
