<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaoexTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ranks', function (Blueprint $table) {
            //$table->increments('id');
            $table->integer('player_id')->unsigned();
            $table->foreign('player_id')->references('id')->on('users');
            $table->integer('gamesPlayed');
            $table->integer('win');
            $table->integer('lose');
            $table->decimal('ratio');
            $table->integer('totalScore');
            $table->timestamps();
        });
        
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('firstName');
            $table->string('lastName');
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('address');
            $table->string('province');
            $table->string('city');
            $table->integer('club_id')->unsigned()->nullable();
            $table->integer('approved_status')->unsigned();
            $table->integer('type')->unsigned();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('Club', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('owner_id')->unsigned();
            $table->foreign('owner_id')->references('id')->on('users');
            $table->string('city');
            $table->string('province');
            $table->string('comment')->nullable();
            $table->timestamps();
        });

        Schema::create('ClubAssistant', function (Blueprint $table) {
            $table->integer('club_id')->unsigned();
            $table->foreign('club_id')->references('id')->on('Club');
            $table->integer('assistant_id')->unsigned();
            $table->foreign('assistant_id')->references('id')->on('users');
            $table->timestamps();
        });

        Schema::create('Tournament', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('club_id')->unsigned();
            $table->foreign('club_id')->references('id')->on('Club');
            $table->timestamps();
        });

        Schema::create('League', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->date('startDate');
            $table->date('endDate');
            $table->timestamps();
        });

        Schema::create('Match', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('club_id')->unsigned();
            $table->foreign('club_id')->references('id')->on('Club');
            $table->string('name');
            $table->string('address');
            $table->time('start_time');
            $table->date('startDate');
            $table->date('endDate');
            $table->integer('winner_id')->unsigned()->nullable();
            $table->foreign('winner_id')->references('id')->on('users');
            $table->timestamps();
        });

        Schema::create('Match_Tournament', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('match_id')->unsigned();
            $table->foreign('match_id')->references('id')->on('Match');
            $table->integer('tournament_id')->unsigned();
            $table->foreign('tournament_id')->references('id')->on('Tournament');
            $table->timestamps();
        });

        Schema::create('Match_League', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('match_id')->unsigned();
            $table->foreign('match_id')->references('id')->on('Match');
            $table->integer('league_id')->unsigned();
            $table->foreign('league_id')->references('id')->on('League');
            $table->timestamps();
        });

        Schema::create('MatchResult', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('player_id')->unsigned();
            $table->foreign('player_id')->references('id')->on('users');
            $table->integer('match_id')->unsigned();
            $table->foreign('match_id')->references('id')->on('Match');
            $table->integer('elimination');
            $table->integer('capture');
            $table->integer('hook');
            $table->integer('winBonus');
            $table->integer('total');
            $table->integer('place');
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
        Schema::drop('MatchResult');
        Schema::drop('Match');
        Schema::drop('League');
        Schema::drop('Tournament');
        Schema::drop('ClubUser');
        Schema::drop('ClubAssistant');
        Schema::drop('Club');
        Schema::dropIfExists('users');

    }
}