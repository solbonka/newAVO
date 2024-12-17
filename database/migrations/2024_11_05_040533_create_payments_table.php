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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('order_id');
            $table->foreign('order_id')->references('id')->on('orders');
            $table->decimal('amount');
            $table->enum('method', ["sbp", "bank_card"]);
            $table->enum('status', ["pending", "waiting_for_capture", "succeeded", "canceled"]);
            $table->string('yookassa_id')->nullable();
            $table->string('url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
