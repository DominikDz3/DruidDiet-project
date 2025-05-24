<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class UpdateUserProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $userId = Auth::id();

        return [
            'name' => 'required|string|max:30',
            'surname' => 'required|string|max:50',
            'email' => [
                'required',
                'string',
                'email',
                'max:50',
                Rule::unique('users')->ignore($userId, 'user_id')
            ],
            'password' => 'nullable|string|min:8|confirmed',
            'allergens' => 'nullable|string',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'Pole imię jest wymagane.',
            'name.max' => 'Imię nie może być dłuższe niż 30 znaków.',
            'surname.required' => 'Pole nazwisko jest wymagane.',
            'surname.max' => 'Nazwisko nie może być dłuższe niż 50 znaków.',
            'email.required' => 'Pole email jest wymagane.',
            'email.email' => 'Podaj poprawny adres email.',
            'email.max' => 'Email nie może być dłuższy niż 50 znaków.',
            'email.unique' => 'Podany adres email jest już zajęty przez innego użytkownika.',
            'password.min' => 'Nowe hasło musi mieć co najmniej 8 znaków.',
            'password.confirmed' => 'Potwierdzenie nowego hasła nie zgadza się.',
        ];
    }
}
