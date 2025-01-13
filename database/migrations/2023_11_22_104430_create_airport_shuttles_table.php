<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAirportShuttlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('airport_shuttles', function (Blueprint $table) {
            $table->id();
            $table->string('date');
            $table->string('transport_id');
            $table->string('price_id');
            $table->string('src');
            $table->string('dst');
            $table->integer('duration');
            $table->integer('distance');
            $table->string('price');
            $table->foreignId('order_id')->nullable()->constrained('orders')->onDelete('cascade');
            $table->integer('order_wedding_id')->nullable();
            $table->string('nav')->nullable();
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
        Schema::dropIfExists('airport_shuttles');
    }
}
