<?php

namespace Core\Auth\Domain\Entities;

use Core\Auth\Domain\Entities\ValueObjects\PermissionName;

class Permission
{
    private PermissionName $name;

    public function __construct(PermissionName $name)
    {
        $this->name = $name;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name->value,
        ];
    }

    public function equals(Permission $permission): bool
    {
        return $this->name->equals($permission->name);
    }
}
