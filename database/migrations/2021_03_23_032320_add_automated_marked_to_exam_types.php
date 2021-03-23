<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAutomatedMarkedToExamTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('exam_types', function (Blueprint $table) {
            $table->boolean('automated_marked')->default(0)->after('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('exam_types', function (Blueprint $table) {
            $table->dropColumn(['group_id']);
        });
    }
}
