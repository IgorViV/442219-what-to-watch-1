<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddFilmRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'imdb' => ['required', 'regex:/^tt\d+$/', 'unique:films,imdb_id']
        ];
    }

    /**
     * Returns an array of validation error messages imdb_id.
     *
     * @return string[]
     */
    public function messages()
    {
        return [
            'imdb.regex' => 'imdb_id должен быть передан в формате ttNNNN',
            'imdb.unique' => 'Такой сериал уже есть'
        ];
    }
}
