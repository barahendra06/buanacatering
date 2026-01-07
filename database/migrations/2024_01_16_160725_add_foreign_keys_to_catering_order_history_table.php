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
        Schema::table('catering_order_history', function (Blueprint $table) {
            $table->foreign('new_catering_order_status_id', 'coh_ncos_fk')->references('id')->on('catering_order_status')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('old_catering_order_status_id', 'coh_ocos_fk')->references('id')->on('catering_order_status')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('catering_order_id', 'coh_co_fk')->references('id')->on('catering_orders')->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('catering_order_history', function (Blueprint $table) {
            $table->dropForeign('coh_ncos_fk');
            $table->dropForeign('coh_ocos_fk');
            $table->dropForeign('coh_co_fk');
        });
    }
};
