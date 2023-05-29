<?php

namespace Core\PurchaseRecords\Infrastructure\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExportPurchaseRecordsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'period' => ['sometimes',],
            'period.month' => ['int', 'gte:1', 'lte:12', 'required_with:period'],
            'period.year' => ['int', 'gte:1900', 'required_with:period'],
        ];
    }
}
