<?php

namespace Core\Auth\Infrastructure\Resources;

use Core\Auth\Domain\Entities\Permission;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PermissionResource extends JsonResource
{
    /**
     * @var Permission
     */
    public $resource;

    public function toArray(Request $request): array
    {
        return $this->resource->toArray();
    }
}
