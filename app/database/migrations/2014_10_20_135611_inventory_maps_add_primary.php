<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InventoryMapsAddPrimary extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inventory_maps', function ($table) {
            $table->dropPrimary(['property_id', 'channel_id', 'room_id'], 'inventory_maps_pid_cid_rid');
            $table->primary(['property_id', 'channel_id', 'room_id', 'plan_code'], 'inventory_maps_pid_cid_rid_pc');
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
