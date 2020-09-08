<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCampaignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id('id');
            $table->float('attackers_score');
            $table->foreignId('constellation_id')->references('id')->on('constellations');
            $table->foreignId('alliance_id')->references('id')->on('alliances');
            $table->float('defenders_score');
            $table->string('event_type');
            $table->foreignId('system_id')->references('id')->on('systems');
            $table->dateTime('start_time');
            $table->foreignId('structure_id')->references('id')->on('structures');
            $table->timestamps();

            $table->index('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('campaigns');
    }
}
