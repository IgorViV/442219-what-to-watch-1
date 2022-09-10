<?php

namespace App\Support\Import;

use App\Models\Film;
use App\Models\Genre;
use Illuminate\Support\Facades\Http;

class OmdbRepository implements FilmRepository
{
    /**
     * Gets information about the movie for moderation.
     *
     * @param string $imdbId
     * @return array|null
     */
    public function getFilm(string $imdbId): ?array
    {
        $data = $this->api($imdbId);

        if ($data->clientError()) {
            return null;
        }

        $film = Film::firstOrNew(['imdb_id' => $imdbId]);
        $film->fill([
            'name' => $film['name'] ?? $data['Title'],
            'poster_image' => $data['Poster'],
            'background_color' => '#ffffff',
            'description' => $data['Plot'],
            'rating' => 1,
            'run_time' => intval($data['Runtime']),
            'released' => date('Y', strtotime($data['Released'])),
            'status' => Film::FILM_MODERATE,
            'imdb_id' => $film['imdb_id'] ?? $data['imdbID'],
        ]);

        return [
            'film' => $film,
            'genres' => explode(', ', $data['Genre']),
            'stars' => explode(', ', $data['Actors']),
            'directors' => explode(', ', $data['Director']),
        ];
    }

    /**
     * Getting a response from the service OMDb API.
     *
     * @param string $imdbId
     * @return \GuzzleHttp\Promise\PromiseInterface|\Illuminate\Http\Client\Response
     */
    private function api(string $imdbId)
    {
        return Http::get(config('services.omdbapi.url'),
            [
                'apikey' => config('services.omdbapi.apikey'),
                'i' => $imdbId,
            ]
        );
    }
}
