<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_answers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('quiz_attempt_id')->unsigned();
            $table->foreign('quiz_attempt_id')->references('id')->on('quiz_attempts')
                    ->onUpdate('cascade')->onDelete('cascade');
            $table->integer('question_id')->unsigned();
            $table->foreign('question_id')->references('id')->on('questions')
                    ->onUpdate('cascade')->onDelete('cascade');

            $table->text('answer');

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
        Schema::dropIfExists('student_answers');
    }
}
