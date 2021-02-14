<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stations', function (Blueprint $table) {
            $table->id()->index();
            $table->foreignId('system_id')->index();
            $table->foreignId('item_id')->index();
            $table->foreignId('user_id')->nullable();
            $table->string('text')->nullable();
            $table->integer('station_status_id')->default(1)->index();
            $table->foreignId('gunner_id')->nullable();
            $table->timestamp('out_time')->nullable();
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
        Schema::dropIfExists('stations');
    }
}
