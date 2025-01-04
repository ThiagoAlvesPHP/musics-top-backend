<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginPostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:8|max:255',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'email.required' => 'O campo de e-mail deve ser preenchido!',
            'email.email' => 'O campo de e-mail é inválido!',
            'email.exists' => 'Não existe um usuário com este e-mail!',
            'password.required' => 'O campo de senha deve ser preenchido!',
            'password.min' => 'O campo de senha deve ter no mínimo 8 caracteres!',
            'password.max' => 'O campo de senha deve ter no máximo 8 caracteres!',
        ];
    }
}
