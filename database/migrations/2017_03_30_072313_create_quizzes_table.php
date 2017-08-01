<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuizzesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quizzes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('unit_id')->unsigned();
            $table->foreign('unit_id')->references('id')->on('units')
                    ->onUpdate('cascade')->onDelete('cascade');
            $table->string('semester'); // S1
            $table->integer('year');    // 2017
            $table->string('title');    // e.g: RAT1
            $table->string('type');     // individual/group=
            $table->integer('show_questions')->unsigned(); // how many questions can be shown

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
        Schema::dropIfExists('quizzes');
    }
}
