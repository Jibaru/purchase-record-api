<?php

namespace Core\Vouchers\Domain\Entities\ValueObjects;

class VoucherContent
{
    public readonly array $value;

    public function __construct(array $value)
    {
        $this->value = $value;
    }

    public static function fromJson(string $json): self
    {
        return new self(json_decode($json, true));
    }
}
