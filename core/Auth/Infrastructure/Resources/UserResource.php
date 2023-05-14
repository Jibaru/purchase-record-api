<?php

namespace Core\Auth\Infrastructure\Resources;

use Core\Auth\Domain\Entities\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * @var User
     */
    public $resource;

    public function toArray(Request $request): array
    {
        $user = $this->resource->toArray();
        unset($user['password']);
        return $user;
    }
}
