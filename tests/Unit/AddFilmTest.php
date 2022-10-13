<?php

namespace Tests\Unit;

use App\Jobs\AddFilmJob;
use App\Jobs\SaveFilmJob;
use App\Models\Film;
use App\Support\Import\FilmRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Mockery\MockInterface;
use Tests\TestCase;

class AddFilmTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Checking the movie saving task call.
     *
     * @return void
     */
    public function testCallingAddFilmJob()
    {
        Queue::fake();

        $film = Film::factory()->make(['imdb_id' => 'tt0111161']);

        $repository = $this->mock(FilmRepository::class, function (MockInterface $mock) use ($film) {
            $mock->shouldReceive('getFilm')->andReturn(['film' => $film, 'genres' => []])->once();
        });

        (new AddFilmJob('tt0111161'))->handle($repository);

        Queue::assertPushed(function (SaveFilmJob $job) use ($film) {
            return $job->data['film'] === $film;
        });
    }

    /**
     * Checking for not calling the movie saving task if the repository returned an empty response.
     *
     * @return void
     */
    public function testNotCallingAddFilmJob()
    {
        Queue::fake();

        $repository = $this->mock(FilmRepository::class, function (MockInterface $mock) {
            $mock->shouldReceive('getFilm')->andReturn(null)->once();
        });

        (new AddFilmJob('tt0111161'))->handle($repository);

        Queue::assertNotPushed(SaveFilmJob::class);
    }
}
