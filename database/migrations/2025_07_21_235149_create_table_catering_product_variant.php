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
        Schema::create('catering_product_variant', function (Blueprint $table) {
            $table->id();
            $table->foreignId('catering_product_id')->references('id')->on('catering_product')->onUpdate('cascade')->onDelete('restrict');
            $table->string('name');
            $table->string('description')->nullable();
            $table->integer('price')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catering_product_variant');
    }
};
