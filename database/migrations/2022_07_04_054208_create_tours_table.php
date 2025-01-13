<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateToursTable extends Migration
{
    public function up()
    {
        Schema::create('tours', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('partners_id')->nullable();
            $table->string('name');
            $table->longText('destinations');
            $table->string('code')->uniqid;
            $table->string('type');
            $table->string('location');
            $table->string('duration');
            $table->longText('description');
            $table->longText('include');
            $table->longText('itinerary');
            $table->longText('additional_info')->nullable();
            $table->longText('cancellation_policy')->nullable();
            $table->integer('contract_rate');
            $table->integer('markup');
            $table->integer('qty');
            $table->string('status');
            $table->integer('author_id');
            $table->text('cover');
            $table->softDeletes();
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('tours');
    }
}
