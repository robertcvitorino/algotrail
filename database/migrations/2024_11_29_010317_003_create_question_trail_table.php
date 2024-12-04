<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('question_trail', function (Blueprint $table) {
            $table->bigInteger('question_id')->unsigned();
            $table->bigInteger('trail_id')->unsigned();
            $table->integer('level');

            $table
                ->foreign('question_id')
                ->references('id')
                ->on('questions')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table
                ->foreign('trail_id')
                ->references('id')
                ->on('trails')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('question_trail');
    }
};
