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
        Schema::table('catering_reviews', function (Blueprint $table) {
            $table->foreignId('catering_product_id')->after('id')->nullable()->constrained('catering_product')->onDelete('cascade');
            $table->foreignId('catering_product_package_id')->after('catering_product_id')->nullable()->constrained('catering_product_package')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('catering_reviews', function (Blueprint $table) {
            $table->dropForeign(['catering_product_id']);
            $table->dropColumn('catering_product_id');
            $table->dropForeign(['catering_product_package_id']);
            $table->dropColumn('catering_product_package_id');
        });
    }
};
