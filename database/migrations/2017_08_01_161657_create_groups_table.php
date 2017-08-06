<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('quiz_id')->unsigned();
            $table->foreign('quiz_id')->references('id')->on('quizzes')
                    ->onUpdate('cascade')->onDelete('cascade');

            $table->integer('group_number');
            $table->boolean('is_open');
            $table->boolean('is_randomized');
            $table->date('test_date')->nullable();   // 01/07/2017
            $table->integer('duration')->nullable(); // 30, 60, 90, 120, 150, 180 minutes
            $table->text('chosen_questions')->nullable(); // [{"question_id": "4"}, {...}]
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
        Schema::dropIfExists('groups');
    }
}
