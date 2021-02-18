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
            $table->foreignId('corp_id')->nullable();
            $table->foreignId('user_id')->nullable();
            $table->string('text')->nullable();
            $table->integer('station_status_id')->default(1)->index();
            $table->foreignId('gunner_id')->nullable();
            $table->timestamp('out_time')->nullable();
            $table->timestamp('repair_time')->nullable();
            $table->string('r_hash')->nullable();
            $table->dateTime('r_updated_at')->nullable();
            $table->string('r_fitted')->nullable();
            $table->integer('r_capital_shipyard', 1)->default(0);
            $table->integer('r_hyasyoda', 1)->default(0);
            $table->integer('r_invention', 1)->default(0);
            $table->integer('r_manufacturing', 1)->default(0);
            $table->integer('r_research', 1)->default(0);
            $table->integer('r_supercapital_shipyard', 1)->default(0);
            $table->integer('r_biochemical', 1)->default(0);
            $table->integer('r_hybrid', 1)->default(0);
            $table->integer('r_moon_drilling', 1)->default(0);
            $table->integer('r_reprocessing', 1)->default(0);
            $table->integer('r_point_defense', 1)->default(0);
            $table->integer('r_dooms_day', 1)->default(0);
            $table->integer('r_guide_bombs', 1)->default(0);
            $table->integer('r_anti_cap', 1)->default(0);
            $table->integer('r_anti_subcap', 1)->default(0);
            $table->integer('r_t2_rigged', 1)->default(0);
            $table->integer('r_cloning', 1)->default(0);
            $table->integer('r_composite', 1)->default(0);
            $table->timestamp('status_update')->nullable();
            $table->timestamp('timestamp')->nullable();
            $table->string('r_cored')->nullable();
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
