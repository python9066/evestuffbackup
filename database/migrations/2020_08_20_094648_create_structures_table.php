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
            $table->foreignId('alliance_id');
            $table->foreignId('solar_system_id');
            $table->foreignId('structure_id');
            $table->foreignId('structure_type_id');
            $table->float('amd');
            $table->timestamp('vulnerable_end_time',);
            $table->timestamp('vulnerable_start_time',);
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
        Schema::dropIfExists('structures');
    }
}
