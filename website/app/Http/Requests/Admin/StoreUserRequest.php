<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
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
            'name' => 'required|string|max:30',
            'surname' => 'required|string|max:50',
            'email' => 'required|string|email|max:50|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => ['required', Rule::in(['user', 'admin'])],
            'loyalty_points' => 'nullable|numeric|min:0',
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
            'email.unique' => 'Podany adres email jest już zajęty.',
            'password.required' => 'Pole hasło jest wymagane.',
            'password.min' => 'Hasło musi mieć co najmniej 8 znaków.',
            'password.confirmed' => 'Potwierdzenie hasła nie zgadza się.',
            'role.required' => 'Pole rola jest wymagane.',
            'role.in' => 'Wybrana rola jest nieprawidłowa.',
            'loyalty_points.numeric' => 'Punkty lojalnościowe muszą być liczbą.',
            'loyalty_points.min' => 'Punkty lojalnościowe nie mogą być ujemne.',
        ];
    }
}
