<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPrimaryKeyToInventoryMap extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inventory_maps', function ($table) {
            $table->dropColumn('inventory_id');
            $table->string('inventory_code');
            $table->primary(['property_id', 'channel_id', 'room_id'], 'inventory_maps_pid_cid_rid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }

}
