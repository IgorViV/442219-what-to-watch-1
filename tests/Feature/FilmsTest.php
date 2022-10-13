<?php

namespace Tests\Feature;

use App\Jobs\AddFilmJob;
use App\Models\Film;
use App\Models\Genre;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class FilmsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test for getting a list of movies.
     *
     * @return void
     * @throws \Exception
     */
    public function testFilmsList()
    {
        $count = random_int(2, 10);
        Film::factory()->count($count)->hasAttached(Genre::factory())->create();

        $response = $this->getJson(route('films.index'));

        $response->assertStatus(200);
        $response->assertJsonStructure(['data' => [], 'links' => [], 'total']);
        $response->assertJsonFragment(['total' => $count]);
    }

    /**
     * Test of getting a movie by its id.
     *
     * @return void
     */
    public function testGetOneFilm()
    {
        $film = Film::factory()->create();

        $response = $this->getJson(route('films.show', $film->id));

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'name' => $film->name,
            'video_link' => $film->video_link,
            'description' => $film->description,
            'run_time' => $film->run_time,
            'released' => $film->released,
            'imdb_id' => $film->imdb_id,
            'status' => $film->status,
        ]);
    }

    /**
     * Test of a request to add a new movie.
     *
     * @return void
     */
    public function testRequestAddingFilm()
    {
        Queue::fake();

        Sanctum::actingAs(User::factory()->create());

        $response = $this->postJson(route('films.store'), ['imdb' => 'tt0111161']);

        Queue::assertPushed(AddFilmJob::class);

        $response->assertStatus(201);
    }

    /**
     * Testing adding an already existing film.
     *
     * @return void
     */
    public function testAddingExistingFilm()
    {
        $film = Film::factory()->create(['imdb_id' => 'tt0111161']);

        Sanctum::actingAs(User::factory()->create());

        $response = $this->postJson(route('films.store'), ['imdb' => $film->imdb_id]);

        $response->assertStatus(422);
        $response->assertJsonStructure(['errors' => ['imdb']]);
        $response->assertJsonFragment(['imdb' => ['Такой фильм уже есть']]);
    }

    /**
     * Testing the addition of a movie by an unidentified user.
     *
     * @return void
     */
    public function testAddFilmUnidentifiedUser()
    {
        $response = $this->postJson(route('films.store'), ['imdb' => 'tt0111161']);

        $response->assertStatus(401);
        $response->assertJsonFragment(['message' => 'Запрос требует аутентификации.']);
    }

    /**
     * Testing validation film id
     *
     * @return void
     */
    public function testValidationAddFilm()
    {
        Sanctum::actingAs(User::factory()->create());

        $response = $this->postJson(route('films.store'), ['imdb' => '0111161']);

        $response->assertStatus(422);
        $response->assertJsonStructure(['errors' => ['imdb']]);
        $response->assertJsonFragment(['imdb' => ['imdb_id должен быть передан в формате ttNNNN']]);
    }
}
