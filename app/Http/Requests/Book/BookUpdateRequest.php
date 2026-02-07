<?php

namespace App\Http\Requests\Book;

use Illuminate\Foundation\Http\FormRequest;

class BookUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Правила валидации для редактирования книги
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'description' => 'sometimes|string|min:10',
            'genre' => 'required|string|max:100',
            'cover' => 'sometimes|url|max:500',
            'is18Plus' => 'sometimes|boolean',
        ];
    }

    /**
     * Кастомные сообщения
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Название книги обязательно для заполнения',
            'title.string' => 'Название книги должно быть строкой',

            'author.required' => 'Указание автора обязательно для заполнения',
            'author.string' => 'Автор книги должен быть строкой',

            'description.string' => 'Описание книги должно быть строкой',
            'description.min' => 'Описание должно содержать минимум 10 символов',

            'genre.string' => 'Жанр книги должен быть строкой',

            'cover.url' => 'Ссылка на обложку должна быть валидным URL',
        ];
    }
}
