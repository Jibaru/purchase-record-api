<?php

namespace Core\Inventory\Infrastructure\Resources;

use Core\Inventory\Domain\Dtos\SimpleItemDto;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SimpleItemDtoResource extends JsonResource
{
    /**
     * @var SimpleItemDto
     */
    public $resource;

    public function toArray(Request $request): array
    {
        return [
            'reference' => $this->resource->reference,
            'code' => $this->resource->code,
            'description' => $this->resource->description,
            'total_quantity' => $this->resource->totalQuantity,
            'unit_price_average' => $this->resource->unitPriceAverage,
        ];
    }
}
