<?php

namespace Core\Auth\Infrastructure\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddPermissionToUserHandlerRequest extends FormRequest
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
                'in:manage-inventory,manage-users,manage-vouchers,manage-purchase-records',
            ],
        ];
    }
}
