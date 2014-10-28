<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateReservationsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('property_id')->index();
            $table->integer('channel_id')->index();
            $table->integer('room_id')->index();
            $table->string('res_id', 100)->index();
            $table->timestamp('res_created');
            $table->string('res_inventory', 100);
            $table->string('res_plan', 100);
            $table->enum('status', ['booked', 'cancelled', 'pending'])->index();
            $table->timestamp('date_arrival');
            $table->timestamp('date_departure');
            $table->tinyInteger('count_adult');
            $table->tinyInteger('count_child');
            $table->tinyInteger('count_child_age')->nullable();
            $table->string('guest_firstname', 100);
            $table->string('guest_lastname', 100);
            $table->string('phone', 100);
            $table->string('email', 100);
            $table->text('cc_details');
            $table->text('comments');
            $table->decimal('total');
            $table->string('currency', '3');
            $table->tinyInteger('modified');
            $table->string('res_source', '100');
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
        Schema::drop('reservations');
    }

}
