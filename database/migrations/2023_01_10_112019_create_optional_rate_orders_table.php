<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOptionalRateOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('optional_rate_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->string("rsv_id")->nullable();
            $table->string("optional_rate_id");
            $table->string("number_of_guest");
            $table->string("service_date");
            $table->string("price_pax");
            $table->string("price_total");
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('optional_rate_orders');
    }
}
