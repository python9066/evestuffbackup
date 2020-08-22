<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlliancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alliances', function (Blueprint $table) {
            $table->id('id');
            $table->integer('creator_corporation_id')->nullable();
            $table->integer('creator_id')->nullable();
            $table->dateTime('date_founded')->nullable();
            $table->integer('executor_corporation_id')->nullable();
            $table->string('name',)->nullable();
            $table->string('ticker',)->nullable();
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
        Schema::dropIfExists('alliances');
    }
}
