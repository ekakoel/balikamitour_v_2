<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContractsTable extends Migration
{

    public function up()
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->string('hotels_id');
            $table->string('name');
            $table->text('file_name');
            $table->date('period_start');
            $table->date('period_end');
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('contracts');
    }
}
