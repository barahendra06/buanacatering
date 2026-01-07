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
        Schema::create('catering_order_history', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('catering_order_id')->index('catering_order_history_catering_order_id_foreign');
            $table->string('title')->nullable();
            $table->string('description', 500)->nullable();
            $table->unsignedBigInteger('old_catering_order_status_id')->nullable()->index('catering_order_history_old_catering_order_status_id_foreign');
            $table->unsignedBigInteger('new_catering_order_status_id')->nullable()->index('catering_order_history_new_catering_order_status_id_foreign');
            $table->unsignedInteger('updated_by_id')->nullable();
            $table->tinyInteger('is_visible')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catering_order_history');
    }
};
