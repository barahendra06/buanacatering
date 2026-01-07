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
        Schema::create('catering_shopping_cart', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('catering_product_package_id')->index('catering_shopping_cart_catering_product_package_id_foreign');
            $table->unsignedBigInteger('catering_product_id')->index('catering_shopping_cart_catering_product_id_foreign');
            $table->unsignedBigInteger('catering_product_variant_id')->index('catering_shopping_cart_catering_product_variant_id_foreign');
            $table->unsignedInteger('user_id')->index('catering_shopping_cart_user_id_foreign');
            $table->integer('quantity')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catering_shopping_cart');
    }
};
