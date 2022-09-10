<?php

namespace App\Support\Import;

use App\Models\Film;
use Illuminate\Support\Facades\Http;

class AcademyRepository implements FilmRepository
{

    /**
     * @inheritDoc
     */
    public function getFilm(string $imdbId): ?array
    {
        $data = $this->api($imdbId);

        if ($data->clientError()) {
            return null;
        }

        $film = Film::firstOrNew(['imdb_id' => $imdbId]);
        $film->fill([
            'name' => $film['name'] ?? $data['name'],
            'poster_image' => $data['poster'],
            'preview_image' => $data['icon'],
            'background_image' => $data['background'],
            'background_color' => '#ffffff',
            'video_link' => $data['video'],
            'preview_video_link' => $data['preview'],
            'description' => $data['desc'],
            'rating' => 1,
            'run_time' => $data['run_time'],
            'released' => $data['released'],
            'status' => Film::FILM_MODERATE,
        ]);

        return [
            'film' => $film,
            'genres' => $data['genres'],
            'stars' => $data['actors'],
            'directors' => is_array($data['director']) ? $data['director'] : array($data['director']),
        ];
    }

    /**
     * Getting a response from the service HTML Academy API.
     *
     * @param string $imdbId
     * @return \GuzzleHttp\Promise\PromiseInterface|\Illuminate\Http\Client\Response
     */
    private function api(string $imdbId)
    {
        return Http::get(config('services.academyrep.url') . $imdbId);
    }
}
