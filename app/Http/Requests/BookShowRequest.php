<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookShowRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['string'],
            'summary_title' => ['string'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
