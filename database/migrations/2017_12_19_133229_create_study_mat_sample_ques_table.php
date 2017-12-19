<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudyMatSampleQuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('study_mat_sample_ques', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('study_mat_id')->unsigned();
            $table->foreign('study_mat_id')->references('id')->on('study_mats')->onDelete('cascade');
            $table->text('questions')->nullable();
            $table->text('answers')->nullable();
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
        Schema::dropIfExists('study_mat_sample_ques');
    }
}
