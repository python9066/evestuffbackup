<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('system_id')->references('id')->on('systems');
            $table->foreignId('item_id')->references('id')->on('items');
            $table->foreignId('notification_type_id')->references('id')->on('notification_types');
            $table->integer('status_id')->default(1);
            $table->dateTime('timestamp');
            $table->bigInteger('si_id');
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
        Schema::dropIfExists('notifications');
    }
}
