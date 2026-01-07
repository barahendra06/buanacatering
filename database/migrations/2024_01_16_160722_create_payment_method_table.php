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
        Schema::create('payment_method', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('name', 30);
            $table->string('alias', 30)->nullable();
            $table->string('image_path', 250);
            $table->boolean('is_active')->nullable()->default(false);
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('deleted_at')->nullable();
        });

        DB::table('payment_method')->insert([
            [
                'name' => 'Transfer',
                'alias' => 'Transfer BCA',
                'image_path' => 'https://dblacademy.com/img/payment/payment-bca.png',
                'is_active' => 0,
            ],
            [
                'name' => 'Cash',
                'alias' => 'Tunai',
                'image_path' => null,
                'is_active' => 0,
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_method');
    }
};
