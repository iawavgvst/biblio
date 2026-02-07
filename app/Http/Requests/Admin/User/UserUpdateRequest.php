<?php

namespace App\Http\Requests\Admin\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Правила валидации для редактирования данных пользователя
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userId = auth()->id();;

        return [
            'name' =>'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($userId),
            ],
        ];
    }

    /**
     * Кастомные сообщения
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Имя обязательно для заполнения.',
            'name.string' => 'ПИмя должно быть строкой.',
            'name.max' => 'Имя не должно превышать 255 символов.',

            'email.required' => 'Email обязателен для заполнения.',
            'email.email' => 'Введите корректный адрес электронной почты в формате "example@some.domain".',
            'email.max' => 'Email не должен превышать 255 символов.',
            'email.unique' => 'Этот адрес электронной почты уже занят.',
        ];
    }
}
