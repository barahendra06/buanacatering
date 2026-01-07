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
        Schema::create('catering_order_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('catering_order_id')->index('catering_order_details_catering_order_id_foreign');
            $table->unsignedBigInteger('catering_product_package_id')->nullable()->index('catering_order_details_catering_product_package_id_foreign');
            $table->unsignedBigInteger('catering_product_id')->nullable()->index('catering_order_details_catering_product_id_foreign');
            $table->unsignedBigInteger('catering_product_variant_id')->nullable()->index('catering_order_details_catering_product_variant_id_foreign');
            $table->double('unit_price', null, 0);
            $table->integer('quantity');
            $table->double('total_price', null, 0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catering_order_details');
    }
};
