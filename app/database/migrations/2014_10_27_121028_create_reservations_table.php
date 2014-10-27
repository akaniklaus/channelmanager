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
            $table->string('reservation_id', 100)->index();
            $table->enum('status', ['booked', 'cancelled', 'pending'])->index();
            $table->date('date_arrival');
            $table->date('date_departure');
            $table->string('guest_firstname', 100);
            $table->string('guest_lastname', 100);
            $table->string('phone', 100);
            $table->string('email', 100);
            $table->integer('guest_count');
            $table->text('cc_details');
            $table->text('comments');
            $table->decimal('total');
            $table->dateTime('res_created');
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
