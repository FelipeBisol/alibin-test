<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => ['required', 'string'],
            'email' => ['string', 'email', 'unique:users,email'],
            'password' => ['required'],
            'password_confirmation' => ['required', 'same:password']
        ];
    }

    public function authorize()
    {
        return true;
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'Email ja utilizado'
        ];
    }
}
