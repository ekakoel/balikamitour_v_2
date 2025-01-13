<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHotelPricesTable extends Migration
{
    public function up()
    {
        Schema::create('hotel_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId("hotels_id")->constraint("hotels")->onDelete("cascade");
            $table->foreignId("rooms_id")->constraint("hotelroom")->onDelete("cascade");
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('markup')->nullable();
            $table->integer('kick_back')->nullable();
            $table->integer('contract_rate');
            $table->integer('author');
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('hotel_prices');
    }
}
