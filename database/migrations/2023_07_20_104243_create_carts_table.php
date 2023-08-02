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
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('user_name')->nullable();
            $table->string('seller_id')->nullable();
            $table->string('seller_type')->nullable();
            $table->string('product_id')->nullable();
            $table->string('product_title')->nullable();
            $table->string('product_image')->nullable();
            $table->string('price')->nullable();
            $table->string('quantity')->nullable();
            $table->string('color')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
