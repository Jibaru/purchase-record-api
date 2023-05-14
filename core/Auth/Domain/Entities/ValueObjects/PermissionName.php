<?php

namespace Core\Auth\Domain\Entities\ValueObjects;

class PermissionName
{
    const MANAGE_USERS = 'manage-users';
    const MANAGE_VOUCHERS = 'manage-vouchers';
    const MANAGE_PURCHASE_RECORDS = 'manage-purchase-records';
    const MANAGE_INVENTORY = 'manage-inventory';

    public readonly string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function equals(PermissionName $name): bool
    {
        return $this->value == $name->value;
    }
}
