<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetTokenRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => ['required', 'exists:users,email'],
            'password' => ['required']
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
