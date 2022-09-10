<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('films', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name', 255);
            $table->string('poster_image', 255)
                ->nullable();
            $table->string('preview_image', 255)
                ->nullable();
            $table->string('background_image', 255)
                ->nullable();
            $table->char('background_color', 9)
                ->default("#ffffff");
            $table->string('video_link', 255)
                ->nullable();
            $table->string('preview_video_link', 255)
                ->nullable();
            $table->string('description', 1000)
                ->nullable();
            $table->decimal('rating', 2, 1)
                ->nullable();
            $table->smallInteger('run_time')
                ->nullable();
            $table->integer('released')
                ->nullable();
            $table->tinyInteger('status');
//          статус из списка: pending, on moderation, ready
            $table->string('imdb_id', 25)
                ->unique();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('films');
    }
};
