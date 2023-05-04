<?php

namespace Core\Auth\Infrastructure\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LogInUserRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'email',
            ],
            'password' => [
                'required',
            ],
        ];
    }
}
