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
        Schema::create('catering_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('order_number')->nullable();
            $table->unsignedInteger('user_id')->index('catering_orders_user_id_foreign');
            $table->unsignedBigInteger('catering_customer_id')->nullable()->index('catering_orders_catering_customer_id_foreign');
            $table->double('subtotal', null, 0)->nullable();
            $table->dateTime('time_limit')->nullable();
            $table->integer('payment_method_id')->nullable()->index('payment_method_fk');
            $table->string('receipt_number', 50)->nullable();
            $table->unsignedBigInteger('catering_order_status_id')->index('catering_orders_catering_order_status_id_foreign');
            $table->dateTime('completed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catering_orders');
    }
};
