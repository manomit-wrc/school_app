<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('question_answers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('subject_id',15);
            $table->string('exam_id',15);
            $table->string('area_id',15);
            $table->string('section_id',15);
            $table->string('level',15);
            $table->text('question');
            $table->text('optionA')->nullable();
            $table->text('optionB')->nullable();
            $table->text('optionC')->nullable();
            $table->text('optionD')->nullable();
            $table->string('correct_answer',15);
            $table->string('status',15);
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
        Schema::dropIfExists('question_answers');
    }
}
