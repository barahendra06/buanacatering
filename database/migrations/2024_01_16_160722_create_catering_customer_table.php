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
        Schema::create('catering_customer', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('address', 300);
            // $table->unsignedInteger('province_id')->index('catering_customer_province_id_foreign');
            // $table->integer('city_id')->index('catering_customer_city_id_foreign');
            // $table->integer('district_id')->nullable()->index('catering_customer_kecamatan_id_foreign');
            $table->string('phone_number');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catering_customer_address');
    }
};
