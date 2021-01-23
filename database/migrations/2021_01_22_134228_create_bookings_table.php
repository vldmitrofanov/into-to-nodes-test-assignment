<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('client_id')->unsigned()->nullable();
            $table->bigInteger('schedule_group_id')->unsigned()->nullable();
            $table->string('description')->nullable();
            $table->time('time_start');
            $table->time('time_end');
            $table->date('date');
            $table->bigInteger('expert_id')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('expert_id')->references('id')->on('experts')->onDelete('cascade');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->foreign('schedule_group_id')->references('id')->on('experts_availabilities')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bookings');
    }
}
