<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCampaignSystemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaign_systems', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaigan_id');
            $table->foreignId('system_id');
            $table->foreignId('campaigan_user_id')->nullable();
            $table->foreignId('campaigan_system_status_id')->default(1);
            $table->string('node_id',8);
            $table->text('notes')->nullable();
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
        Schema::dropIfExists('campaign_systems');
    }
}
