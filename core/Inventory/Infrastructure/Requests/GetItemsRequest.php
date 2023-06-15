<?php

namespace Core\Inventory\Infrastructure\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetItemsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'page' => ['int', 'gt:0', 'sometimes'],
            'paginate' => ['int', 'gt:0', 'sometimes'],
            'period' => ['sometimes',],
            'period.month' => ['int', 'gte:1', 'lte:12', 'required_with:period'],
            'period.year' => ['int', 'gte:1900', 'required_with:period'],
        ];
    }
}
