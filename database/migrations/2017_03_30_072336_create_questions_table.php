<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('quiz_id')->unsigned();
            $table->foreign('quiz_id')->references('id')->on('quizzes')
                    ->onUpdate('cascade')->onDelete('cascade');

            $table->string('answer_type');    // single answer / rank 1-5
            $table->text('question');
            $table->text('answer1');        // A
            $table->text('answer2');        // B
            $table->text('answer3');        // C
            $table->text('answer4');        // D
            $table->text('answer5');        // E
            $table->text('correct_answer');

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
        Schema::dropIfExists('questions');
    }
}
