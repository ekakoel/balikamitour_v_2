<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOptionalRatesTable extends Migration
{
    public function up()
    {
        Schema::create('optional_rates', function (Blueprint $table) {
            $table->id();
            $table->integer('hotels_id');
            $table->string('name');
            $table->string('service');
            $table->integer('service_id');
            $table->string('type');
            $table->integer('contract_rate');
            $table->integer('markup')->nullable();
            $table->longText('description')->nullable();
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('optional_rates');
    }
}
