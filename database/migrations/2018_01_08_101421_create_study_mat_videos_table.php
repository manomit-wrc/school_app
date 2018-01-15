<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudyMatVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('study_mat_videos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('study_mat_id')->unsigned();
            $table->foreign('study_mat_id')->references('id')->on('study_mats')->onDelete('cascade');
            $table->string('video_name')->nullable();
            $table->text('video_desc')->nullable();
            $table->text('video_file')->nullable();
            $table->string('video_order', '10')->nullable();
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
        Schema::dropIfExists('study_mat_videos');
    }
}
