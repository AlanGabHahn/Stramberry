<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        /**table users*/
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });
        /**table genres*/
        Schema::create('genres', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });
        /**table movies */
        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->integer('release_month');
            $table->integer('release_year');
            $table->bigInteger('genre_id')->unsigned();
            $table->foreign('genre_id')->references('id')->on('genres');
        });
        /**table streamings*/
        Schema::create('streamings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });
        /**relationship table between movies and streaming*/
        Schema::create('movie_streamings', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('movie_id')->unsigned();
            $table->foreign('movie_id')->references('id')->on('movies');
            $table->bigInteger('streaming_id')->unsigned();
            $table->foreign('streaming_id')->references('id')->on('streamings');
        });
        /**table assessments*/
        Schema::create('assessments', function (Blueprint $table) {
            $table->id();
            $table->string('comment');
            $table->integer('rating');
            $table->bigInteger('movie_id')->unsigned();
            $table->foreign('movie_id')->references('id')->on('movies');
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('movies');
        Schema::dropIfExists('streamings');
        Schema::dropIfExists('movie_streamings');
        Schema::dropIfExists('genres');
        Schema::dropIfExists('assessments');
    }
};
