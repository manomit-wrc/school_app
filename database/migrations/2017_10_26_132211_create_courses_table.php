<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('full_name',150)->nullable();
            $table->string('short_name',100)->nullable();
            $table->string('category_id',50)->nullable();
            $table->string('description')->nullable();
            $table->string('description_file',100)->nulable();
            $table->string('start_date',100)->nullable();
            $table->string('end_date',100)->nullable();
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
        Schema::dropIfExists('courses');
    }
}
