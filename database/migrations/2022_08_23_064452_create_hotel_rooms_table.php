<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHotelRoomsTable extends Migration
{
    public function up()
    {
        Schema::create('hotel_rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId("hotels_id")->constraint("hotels")->onDelete("cascade");
            $table->string("cover");
            $table->string("rooms");
            $table->integer("capacity");
            $table->longText("include")->nullable();
            $table->longText("additional_info")->nullable();
            $table->string("status");
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('hotel_rooms');
    }
}
