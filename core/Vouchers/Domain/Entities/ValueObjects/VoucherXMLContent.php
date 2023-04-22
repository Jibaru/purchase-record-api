<?php

namespace Core\Vouchers\Domain\Entities\ValueObjects;

class VoucherXMLContent
{
    public readonly string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }
}
