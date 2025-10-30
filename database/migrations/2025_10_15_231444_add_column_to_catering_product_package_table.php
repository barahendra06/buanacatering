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
        Schema::table('catering_product_package', function (Blueprint $table) {
            $table->enum('is_active', ['1', '0'])->after('price')->nullable()->default('0');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('catering_product_package', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }
};
