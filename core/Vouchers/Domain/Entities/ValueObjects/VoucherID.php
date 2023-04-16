<?php

namespace Core\Vouchers\Domain\Entities\ValueObjects;

use Core\Shared\Uuid;

class VoucherID
{
    public readonly string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function empty(): self
    {
        return new VoucherID(Uuid::make());
    }
}
