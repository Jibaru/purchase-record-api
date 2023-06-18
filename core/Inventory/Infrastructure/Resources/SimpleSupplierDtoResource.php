<?php

namespace Core\Inventory\Infrastructure\Resources;

use Core\Inventory\Domain\Dtos\SimpleSupplierDto;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SimpleSupplierDtoResource extends JsonResource
{
    /**
     * @var SimpleSupplierDto
     */
    public $resource;

    public function toArray(Request $request): array
    {
        return [
            'reference' => $this->resource->reference,
            'type' => $this->resource->type,
            'number' => $this->resource->number,
            'denomination' => $this->resource->denomination,
            'total_items' => $this->resource->totalItems,
            'total_price' => $this->resource->totalPrice,
        ];
    }
}
