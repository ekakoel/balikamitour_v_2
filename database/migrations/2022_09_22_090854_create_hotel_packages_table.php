<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHotelPackagesTable extends Migration
{
    public function up()
    {
        Schema::create('hotel_packages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId("hotels_id")->constraint("hotels")->onDelete("cascade");
            $table->foreignId("rooms_id")->constraint("hotels")->onDelete("cascade");
            $table->string("name");
            $table->string("duration");
            $table->date("stay_period_start");
            $table->date("stay_period_end");
            $table->integer("contract_rate");
            $table->integer("markup");
            $table->string("booking_code")->nullable();
            $table->longText("benefits")->nullable();
            $table->longText("include")->nullable();
            $table->longText("additional_info")->nullable();
            $table->string("status");
            $table->integer('author');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('hotel_packages');
    }
}
