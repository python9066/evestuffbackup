<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStartCampaignSystemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('start_campaign_systems', function (Blueprint $table) {
            $table->id();
            $table->foreignId("start_campaign_id");
            $table->foreignId("campaign_user_id");
            $table->foreignId("campaign_system_status_id");
            $table->integer("base_time");
            $table->dateTime("input_time");
            $table->dateTime("end_time");
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
        Schema::dropIfExists('start_campaign_systems');
    }
}
