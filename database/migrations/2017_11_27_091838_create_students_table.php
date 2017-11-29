<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name',150)->default('');
            $table->string('last_name', 150)->default('');
            $table->string('username',50);
            $table->string('email',150);
            $table->string('password');
            $table->text('address')->nullable();
            $table->string('city',75)->default('');
            $table->string('pincode',6)->default('');
            $table->string('image',50)->default('');
            $table->smallInteger('status')->default('0');
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
        Schema::dropIfExists('students');
    }
}
