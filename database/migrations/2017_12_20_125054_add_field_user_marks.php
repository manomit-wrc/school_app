<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldUserMarks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_marks', function (Blueprint $table) {
            $table->integer('section_id')->after('area_id')->nullable();
            $table->integer('total_correct_ans')->after('percentile')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_marks', function (Blueprint $table) {
            //
        });
    }
}
