<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoggingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('loggings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id');
            $table->foreignId('sola_id')->references('id')->on('campaign_sola_systems');
            $table->foreignId('node_id')->nullable()->references('id')->on('campaign_systems');
            $table->foreignId('user_id');
            $table->foreignId('logging_type_id')->nullable();
            $table->text('text')->nullable();
            $table->timestamps();
        });
        Schema::disableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loggings');
    }
}
