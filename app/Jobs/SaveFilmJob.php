<?php

namespace App\Jobs;

use App\Models\Director;
use App\Models\Film;
use App\Models\Genre;
use App\Models\Star;
use App\Support\Import\FilmRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;

class SaveFilmJob implements ShouldQueue
{
    use Dispatchable, Queueable;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(public array $data)
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(FilmRepository $repository)
    {
        list('film' => $film,
            'genres' => $genres,
            'stars' => $stars,
            'directors' => $directors) = $this->data;
        $film->status = Film::FILM_READY;

        $genresIds = [];
        $directorsIds = [];
        $starsIds = [];

        foreach ($genres as $genre) {
            $genresIds[] = Genre::firstOrCreate(['title' => trim($genre)])->id;
        }

        foreach ($directors as $director) {
            $directorsIds[] = Director::firstOrCreate(['name' => $director])->id;
        }

        foreach ($stars as $star) {
            $starsIds[] = Star::firstOrCreate(['name' => trim($star)])->id;
        }

        DB::beginTransaction();
        $film->save();
        $film->genres()->attach($genresIds);
        $film->directors()->attach($directorsIds);
        $film->stars()->attach($starsIds);
        DB::commit();
    }
}
