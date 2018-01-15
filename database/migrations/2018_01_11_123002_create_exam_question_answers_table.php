<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExamQuestionAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exam_question_answers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('exam_id');
            $table->string('question_type');
            $table->text('question');
            $table->string('option_type', 50);
            $table->text('answer');
            $table->text('correct_answer');
            $table->integer('status');
            $table->integer('rating');
            $table->string('numeric_answer');
            $table->text('explanation_details');
            $table->string('explanation_file');
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
        Schema::dropIfExists('exam_question_answers');
    }
}
