<?php

use App\Models\OperationInfoReconStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        OperationInfoReconStatus::create(['id' => 3, 'name' => 'In System']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        OperationInfoReconStatus::where('id', 3)->delete();
    }
};
