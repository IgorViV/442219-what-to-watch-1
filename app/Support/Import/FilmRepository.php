<?php

namespace App\Support\Import;

interface FilmRepository
{
    /**
     * @param string $imdbId
     * @return array|null
     */
    public function getFilm(string $imdbId): ?array;
}
