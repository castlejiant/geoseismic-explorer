<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSeismicEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seismic_events', function (Blueprint $table) {
            $table->increments('id');
            $table->string('event_id')->unique();
            $table->decimal('mag');
            $table->string('place');
            $table->bigInteger('time');
            $table->bigInteger('updated');
            $table->string('url');
            $table->string('detail');
            $table->decimal('longitude');
            $table->decimal('latitude');
            $table->decimal('depth');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seismic_events');
    }
}
