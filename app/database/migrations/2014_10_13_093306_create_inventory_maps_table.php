<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateInventoryMapsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_maps', function (Blueprint $table) {
            $table->string('name');
            $table->integer('inventory_id');
            $table->integer('room_id');
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
        Schema::drop('inventory_maps');
    }

}
