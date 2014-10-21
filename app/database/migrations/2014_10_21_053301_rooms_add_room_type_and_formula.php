<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RoomsAddRoomTypeAndFormula extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rooms', function ($table) {
            $table->enum('type', ['room', 'plan'])->default('room');
            $table->integer('parent_id');
            $table->enum('formula_type', ['x', '+', '-'])->nullable();
            $table->decimal('formula_value', 10, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rooms', function ($table) {
            $table->dropColumn('type');
            $table->dropColumn('parent_id');
            $table->dropColumn('formula_type');
            $table->dropColumn('formula_value');
        });
    }

}
