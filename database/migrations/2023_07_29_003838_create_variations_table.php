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
        Schema::create('variations', function (Blueprint $table) {
            $table->id();
            $table->string('product_id')->nullable();
            $table->string('color')->nullable();
            $table->string('size')->nullable();
            $table->string('price')->nullable();
            $table->string('discount_price')->nullable();
            $table->string('reseller_price')->nullable();
            $table->string('reseller_quantity')->nullable();
            $table->string('quantity')->nullable();
            $table->string('image1')->nullable();
            $table->string('image2')->nullable();
            $table->string('image3')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('variations');
    }
};
