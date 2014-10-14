<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateInventoryPlansTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_plans', function (Blueprint $table) {
            $table->string('name');
            $table->string('code');
            $table->integer('channel_id');
            $table->string('inventory_code');
            $table->timestamps();
            $table->primary(['channel_id', 'inventory_code', 'code']);
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('inventory_plans');
    }

}
