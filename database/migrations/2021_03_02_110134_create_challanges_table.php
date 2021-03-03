<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChallangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('challanges', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('game_id');
            $table->text('description');
            $table->text('rules');
            $table->text('Rewards');
            $table->string('Platform');
            $table->integer('number_of_participants');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->dateTime('voting_date');
            $table->tinyInteger('close')->nullable();
            $table->enum('status', ['featured', 'Upcoming','completed']);
            $table->enum('featured_status', ['vote', 'submit'])->nullable();
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
        Schema::dropIfExists('challanges');
    }
}
