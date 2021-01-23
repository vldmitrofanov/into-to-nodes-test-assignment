<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpertsAvailabilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('experts_availabilities', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->time('time_start');
            $table->tinyInteger('time_end');
            $table->bigInteger('expert_id')->unsigned();
            $table->timestamps();

            $table->foreign('expert_id')->references('id')->on('experts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('experts_availabilities');
    }
}
