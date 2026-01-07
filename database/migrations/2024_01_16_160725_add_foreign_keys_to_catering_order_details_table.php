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
        Schema::table('catering_order_details', function (Blueprint $table) {
            $table->foreign('catering_order_id', 'cod_co_fk')
                    ->references(['id'])
                    ->on('catering_orders')
                    ->onUpdate('cascade')
                    ->onDelete('restrict');

            $table->foreign('catering_product_package_id', 'cod_spp_fk')
                    ->references(['id'])
                    ->on('catering_product_package')
                    ->onUpdate('cascade')
                    ->onDelete('restrict');

            $table->foreign('catering_product_id', 'cod_cp_fk')
                    ->references(['id'])
                    ->on('catering_product')
                    ->onUpdate('cascade')
                    ->onDelete('restrict');

            $table->foreign('catering_product_variant_id', 'cod_cpv_fk')
                    ->references(['id'])
                    ->on('catering_product_variant')
                    ->onUpdate('cascade')
                    ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('catering_order_details', function (Blueprint $table) {
            $table->dropForeign('cod_co_fk');
            $table->dropForeign('cod_spp_fk');
            $table->dropForeign('cod_cp_fk');
            $table->dropForeign('cod_cpv_fk');
        });
    }
};
