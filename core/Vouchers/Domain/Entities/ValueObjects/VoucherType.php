<?php

namespace Core\Vouchers\Domain\Entities\ValueObjects;

class VoucherType
{
    public readonly string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function invoice(): self
    {
        return new self('invoice');
    }
}
