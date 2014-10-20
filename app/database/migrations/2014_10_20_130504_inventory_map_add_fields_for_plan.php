<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InventoryMapAddFieldsForPlan extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inventory_maps', function ($table) {
            $table->string('plan_code', '100');
            $table->string('plan_name', '100');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('inventory_maps', function ($table) {
            $table->dropColumn('plan_code');
            $table->dropColumn('plan_name');
        });
    }

}
