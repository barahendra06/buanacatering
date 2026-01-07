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
        Schema::table('catering_orders', function (Blueprint $table) {
            $table->foreign('catering_customer_id', 'co_cc_fk')->references('id')->on('catering_customer')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('catering_order_status_id', 'co_sos_fk')->references('id')->on('catering_order_status')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('user_id', 'co_user_fk')->references('id')->on('user')->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('catering_orders', function (Blueprint $table) {
            $table->dropForeign('co_cc_fk');
            $table->dropForeign('co_sos_fk');
            $table->dropForeign('co_user_fk');
        });
    }
};
