<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColoumNameOnOperationUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('new_node_campaign_users', function (Blueprint $table) {
            $table->renameColumn('new_system_node_id', 'new_user_node_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('new_node_campaign_users', function (Blueprint $table) {
            $table->renameColumn('new_user_node_id', 'new_system_node_id');
        });
    }
}
