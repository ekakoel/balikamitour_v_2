<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWeddingAdditionalServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wedding_additional_services', function (Blueprint $table) {
            $table->id();
            $table->string('date');
            $table->string('duration');
            $table->integer('order_wedding_id')->nullable();
            $table->integer('order_id')->nullable();
            $table->integer('wedding_planner_id')->nullable();
            $table->string('type');
            $table->string('service');
            $table->integer('quantity');
            $table->longText('note')->nullable();
            $table->longText('remark')->nullable();
            $table->string('price');
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wedding_additional_services');
    }
}
