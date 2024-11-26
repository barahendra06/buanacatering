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
        $table->id();
        $table->string('image_path');
        $table->string('name');
        $table->text('description');
        $table->string('address');
        $table->string('mobile_phone');
        $table->timestamp();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
