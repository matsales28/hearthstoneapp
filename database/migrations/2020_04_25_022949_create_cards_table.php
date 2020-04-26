<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cards', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('health')->nullable();
            $table->string('playerClass')->nullable();
            $table->string('cardSet')->nullable();
            $table->integer('attack')->nullable();
            $table->string('type')->nullable();
            $table->string('race')->nullable();
            $table->string('faction')->nullable();
            $table->string('text')->nullable();
            $table->string('img')->nullable();
            $table->json('mechanics')->nullable();
            $table->integer('cost')->nullable();
            $table->string('flavor')->nullable();
            
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
        Schema::dropIfExists('cards');
    }
}
