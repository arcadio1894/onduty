<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('informe_id')->unsigned();
            $table->foreign('informe_id')->references('id')->on('informes');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('work_front_id')->unsigned();
            $table->foreign('work_front_id')->references('id')->on('work_fronts');
            $table->integer('area_id')->unsigned();
            $table->foreign('area_id')->references('id')->on('area');
            $table->integer('responsible_id')->unsigned();
            $table->foreign('responsible_id')->references('id')->on('users');
            $table->string('aspect');
            $table->integer('critical_risks_id')->unsigned();
            $table->foreign('critical_risks_id')->references('id')->on('critical_risks');
            $table->string('potential');
            $table->string('state');
            $table->string('image');
            $table->string('image_action');
            $table->date('planned_date');
            $table->date('deadline')->nullable();
            $table->integer('inspections');
            $table->string('description');
            $table->string('actions');
            $table->string('observations');
            $table->softDeletes();
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
        Schema::dropIfExists('reports');
    }
}
