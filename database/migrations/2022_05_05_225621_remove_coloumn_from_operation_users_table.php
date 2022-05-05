<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveColoumnFromOperationUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('operation_users', function (Blueprint $table) {
            Schema::table('operation_users', function (Blueprint $table) {
                $table->dropColumn('end_time');
                $table->dropColumn('input_time');
                $table->dropColumn('base_time');
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('operation_users', function (Blueprint $table) {
            $table->dateTime('end_time')->nullable();
            $table->dateTime('input_time')->nullable();
            $table->integer('base_time')->nullable();
        });
    }
}
Schema::create('new_user_nodes', function (Blueprint $table) {
    $table->id();
    $table->boolean('primery')->default(0);
    $table->foreignId('node_id');
    $table->foreignId('operation_user_id')->nullable();
    $table->foreignId('node_status_id');
    $table->text('notes')->nullable();
    $table->text('attack_notes')->nullable();
    $table->text('attack_adash_link')->nullable();
    $table->dateTime('end_time')->nullable();
    $table->dateTime('input_time')->nullable();
    $table->integer('base_time')->nullable();
    $table->timestamps();
});
