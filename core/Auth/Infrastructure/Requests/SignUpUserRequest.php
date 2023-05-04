<?php

namespace Core\Auth\Infrastructure\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SignUpUserRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
            ],
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
