<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUnitContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('unit_contents', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('unit_id')->unsigned();
            $table->foreign('unit_id')->references('id')->on('units')
                    ->onUpdate('cascade')->onDelete('cascade');

            $table->text('content');

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
        Schema::dropIfExists('unit_contents');
    }
}
