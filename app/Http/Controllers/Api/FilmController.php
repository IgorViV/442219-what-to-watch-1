<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddFilmRequest;
use App\Jobs\AddFilmJob;
use App\Models\Film;
use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;

class FilmController extends Controller
{

    /**
     * Getting a list of movies.
     *
     * @return JsonResponse|Responsable
     * @api {get} /api/films
     */
    public function index(): JsonResponse|Responsable
    {
        return $this->successPaginate(Film::select(['id', 'name'])->paginate(8));
    }

    /**
     * Adding a movie to the database.
     *
     * @param AddFilmRequest $request
     * @return JsonResponse|Responsable
     * @api {post} /api/films
     *
     */
    public function store(AddFilmRequest $request): JsonResponse|Responsable
    {
        // добавляет информацию о фильме в базу данных.
        //
        // При заполнении поля imdb_id и наличии фильма с таким id в базе — возвращается ошибка валидации 422:
        // HTTP_UNPROCESSABLE_ENTITY
        // Или все ОК - HTTP_OK
        // При сохранении проверяем наличие связанных жанров и создаем при отсутствии.

        AddFilmJob::dispatch($request->imdb);

        return $this->success(null, 201);
    }

    /**
     * Getting information about the movie.
     *
     * @api {get} /api/films/{id}
     *
     * @param  int  $id ID film
     * @return JsonResponse|Responsable
     */
    public function show(int $id): JsonResponse|Responsable
    {
        // возвращает информацию о фильме.
        // если фильм не существует 404:
        // HTTP_NOT_FOUND
        // Или все ОК - HTTP_OK
        return $this->success(Film::find($id));
    }

    /**
     * Editing a movie.
     *
     * @api {patch} /api/films/{id}
     *
     * @param  Request  $request
     * @param  int  $id ID film
     * @return JsonResponse|Responsable
     */
    public function update(Request $request, int $id): JsonResponse|Responsable
    {
        // используется как часть функциональности формы добавления фильма в базу.
        // Метод доступен только аутентифицированному пользователю с ролью модератор.
        // Валидация формы редактирорвания.
        // При отсутствии запрошенного в роуте фильма в базе, возвращается 404 ошибка:
        // HTTP_NOT_FOUND
        // Или все ОК - HTTP_OK
        return $this->success();
    }

    /**
     * Getting a list of similar movies
     *
     * @api {get} /api/films/{id}/similar
     *
     * @param int $id ID film
     * @return JsonResponse|Responsable
     */
    public function getSimilar(int $id): JsonResponse|Responsable
    {
        // метод возвращает список из 4 подходящих фильмов.
        // Похожесть определяется принадлежностью к тем же жанрам,
        // что и исходный фильм (любым из имеющихся).
        // HTTP_OK
        return $this->success();
    }
}
