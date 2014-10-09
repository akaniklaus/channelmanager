<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePropertiesChannelsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('properties_channels', function (Blueprint $table) {
            $table->string('login', 100);
            $table->string('password', 100);
            $table->string('hotel_code', 100);
            $table->integer('property_id');
            $table->integer('channel_id');
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
        Schema::drop('properties_channels');
    }

}
