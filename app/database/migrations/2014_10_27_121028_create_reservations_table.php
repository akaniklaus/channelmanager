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
            $table->string('res_id', 100)->index();
            $table->enum('status', ['booked', 'cancelled', 'pending'])->index();
            $table->string('buyer_firstname', 100);
            $table->string('buyer_lastname', 100);
            $table->string('phone', 100);
            $table->string('email', 100);
            $table->string('address', 100);
            $table->string('country', 100);
            $table->string('postal_code', 100);
            $table->string('state', 100);
            $table->text('cc_details');
            $table->text('comments');
            $table->decimal('commission');
            $table->decimal('total');
            $table->string('currency', '3');
            $table->tinyInteger('modified');
            $table->string('res_source', '100');
            $table->string('res_loyalty_id')->nullable();
            $table->decimal('res_cancel_fee')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamp('res_created');
            $table->timestamps();
        });

        Schema::create('bookings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('property_id')->index();
            $table->integer('channel_id')->index();
            $table->integer('room_id')->index();
            $table->string('reservation_id', 100)->index();
            $table->string('rr_id', 100)->index();
            $table->string('inventory', 100)->index();
            $table->string('plan', 100)->index();
            $table->timestamp('date_arrival');
            $table->timestamp('date_departure');
            $table->tinyInteger('count_adult');
            $table->tinyInteger('count_child');
            $table->tinyInteger('count_child_age')->nullable();
            $table->string('guest_firstname', 100);
            $table->string('guest_lastname', 100);
            $table->text('comments');
            $table->decimal('total');
            $table->string('currency', '3');
            $table->string('prices');
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
        Schema::drop('bookings');
        Schema::drop('reservations');
    }

}
