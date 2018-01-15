<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudyMatTheoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('study_mat_theories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('study_mat_id')->unsigned();
            $table->foreign('study_mat_id')->references('id')->on('study_mats')->onDelete('cascade');
            $table->string('theory_name')->nullable();
            $table->text('theory_desc')->nullable();
            $table->text('theory_file')->nullable();
            $table->string('theory_order', '10')->nullable();
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
        Schema::dropIfExists('study_mat_theories');
    }
}
