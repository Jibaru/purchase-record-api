<?php

namespace Core\Vouchers\Infrastructure\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetVouchersRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'page' => [
                'required',
                'int',
                'gt:0',
            ],
            'paginate' => [
                'required',
                'int',
                'gt:0',
            ],
        ];
    }
}
