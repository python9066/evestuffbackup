<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStructuresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('structures', function (Blueprint $table) {
            $table->id('id');
            $table->foreignId('alliance_id')->references('id')->on('alliances');
            $table->foreignId('system_id')->references('id')->on('systems');
            $table->foreignId('item_id')->references('id')->on('items');
            $table->float('adm')->nullable();
            $table->dateTime('vulnerable_end_time',)->nullable();
            $table->dateTime('vulnerable_start_time',)->nullable();
            $table->integer('status')->nullable()->default(0);
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
        Schema::dropIfExists('structures');
    }
}
