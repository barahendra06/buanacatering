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
        Schema::table('catering_shopping_cart', function (Blueprint $table) {
            $table->foreign('catering_product_package_id', 'cpc_cpp_fk')
                    ->references('id')
                    ->on('catering_product_package')
                    ->onDelete('restrict')
                    ->onUpdate('cascade');

            $table->foreign('catering_product_id', 'cpc_cp_fk')
                    ->references('id')
                    ->on('catering_product')
                    ->onDelete('restrict')
                    ->onUpdate('cascade');

            $table->foreign('catering_product_variant_id', 'cpc_cpv_fk')
                    ->references('id')
                    ->on('catering_product_variant')
                    ->onDelete('restrict')
                    ->onUpdate('cascade');

            $table->foreign('user_id', 'spc_user_fk')
                    ->references('id')
                    ->on('user')
                    ->onDelete('restrict')
                    ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('catering_shopping_cart', function (Blueprint $table) {
            $table->dropForeign('cpc_cpp_fk');
            $table->dropForeign('cpc_cp_fk');
            $table->dropForeign('cpc_cpv_fk');
            $table->dropForeign('cpc_user_fk');
        });
    }
};
