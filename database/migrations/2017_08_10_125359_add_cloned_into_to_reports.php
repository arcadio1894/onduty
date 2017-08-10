<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddClonedIntoToReports extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('reports', function($table) {
            $table->integer('cloned_into_id')->unsigned()->nullable();
            $table->foreign('cloned_into_id')->references('id')->on('reports');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reports', function ($table) {
            $table->dropForeign(['cloned_into_id']); // the array is necessary to match with the fk constraint name
            $table->dropColumn('cloned_into_id');
        });
    }
}
