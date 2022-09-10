<?php

namespace App\Jobs;

use App\Support\Import\FilmRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class AddFilmJob implements ShouldQueue
{
    use Dispatchable, Queueable;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(private string $imdbId)
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(FilmRepository $repository)
    {
        $data = $repository->getFilm($this->imdbId);

        if ($data) {
             SaveFilmJob::dispatch($data);
        }
    }
}
