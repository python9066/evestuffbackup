<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStationNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('station_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('system_id');
            $table->integer('status_id')->default(1);
            $table->string('text')->nullable()->default(null);
            $table->foreignId('user_id')->after('si_id')->nullable();
            $table->dateTime('timestamp');
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
        Schema::dropIfExists('station_notifications');
    }
}
