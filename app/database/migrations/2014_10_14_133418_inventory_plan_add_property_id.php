<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InventoryPlanAddPropertyId extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inventory_plans', function ($table) {
            $table->dropPrimary(['channel_id', 'inventory_code', 'code']);
            $table->integer('property_id');
            $table->primary(['channel_id', 'inventory_code', 'code', 'property_id'], 'inventory_plans_cid_ic_c_pid');
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
