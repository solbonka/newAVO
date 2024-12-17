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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('ride_id');
            $table->bigInteger('price_id');
            $table->bigInteger('ba_ticket_id');
            $table->bigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('passenger_phone', 12);
            $table->integer('place');
            $table->decimal('price');
            $table->string('departure_station');
            $table->string('departure_date');
            $table->string('departure_time');
            $table->string('departure_address');
            $table->string('arrival_station');
            $table->string('arrival_date');
            $table->string('arrival_time');
            $table->string('arrival_address');
            $table->string('route_name');
            $table->enum('type', ["Полный", "Детский"]);
            $table->bigInteger('order_id');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->string('ticket_url')->nullable();
            $table->string('status')->default('pending');
            $table->decimal('refunded_amount')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['order_id']);
        });

        Schema::dropIfExists('tickets');
    }
};
