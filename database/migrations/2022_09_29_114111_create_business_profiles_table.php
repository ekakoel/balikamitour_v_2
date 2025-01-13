<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessProfilesTable extends Migration
{
    public function up()
    {
        Schema::create('business_profiles', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("license");
            $table->string("tax_number");
            $table->string("address");
            $table->string("nickname");
            $table->string("tax_id");
            $table->string("type");
            $table->string("map");
            $table->string("phone")->nullable();
            $table->text('logo');
            $table->string('caption')->nullable();
            $table->string('website')->nullable();
            $table->string('instagram')->nullable();
            $table->string('facebook')->nullable();
            $table->string('twitter')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('business_profiles');
    }
}
