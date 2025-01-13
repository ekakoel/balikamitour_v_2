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
        Schema::create('order_hotel_promos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agent_id')->constrained('users')->onDelete('cascade');
            $table->string('orderno');
            $table->string('promo_id');
            $table->date('check_in');
            $table->date('check_out');
            $table->foreignId('hotel_id')->constrained('hotels')->onDelete('cascade');
            $table->foreignId('room_id')->nullable()->constrained('hotel_rooms')->onDelete('set null');
            $table->foreignId('booking_code_id')->nullable()->constrained('booking_codes')->onDelete('set null');
            $table->integer('total_guests');
            $table->integer('total_price');
            $table->text('note')->nullable();
            $table->text('additional_info')->nullable();
            $table->enum('status',['draft','pending','confirmed','approved','cancelled','rejected'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_hotel_promos');
    }
};
