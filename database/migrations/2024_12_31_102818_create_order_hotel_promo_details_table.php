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
        Schema::create('order_hotel_promo_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('hotels')->onDelete('cascade');
            $table->string('promo_id');
            $table->foreignId('extra_bed_id')->nullable()->constrained('extra_beds')->onDelete('set null');
            $table->integer('room_number');
            $table->string('special_event')->nullable();
            $table->date('special_event_date')->nullable();
            $table->integer('number_of_guests');
            $table->string('guests_name');
            $table->longText('benefits');
            $table->longText('include');
            $table->longText('additional_info');
            $table->longText('cancellation_policy');
            $table->string('promo_price');
            $table->string('extra_bed_price');
            $table->string('total_price');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_hotel_promo_details');
    }
};
