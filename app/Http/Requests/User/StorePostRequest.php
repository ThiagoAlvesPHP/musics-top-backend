<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
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
            'name' => 'required|min:3|max:50',
            'email' => 'required|email|unique:users,email',
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
            'name.required' => 'O campo de nome deve ser preenchido!',
            'name.min' => 'O campo de nome deve ter no mínimo 3 caracteres!',
            'name.max' => 'O campo de nome deve ter no máximo 50 caracteres!',
            'email.required' => 'O campo de e-mail deve ser preenchido!',
            'email.email' => 'O campo de e-mail é inválido!',
            'email.unique' => 'Já existe um usuário com este e-mail!',
            'password.required' => 'O campo de senha deve ser preenchido!',
            'password.min' => 'O campo de senha deve ter no mínimo 8 caracteres!',
            'password.max' => 'O campo de senha deve ter no máximo 255 caracteres!',
        ];
    }
}
