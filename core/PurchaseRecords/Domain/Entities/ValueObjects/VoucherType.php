<?php

namespace Core\PurchaseRecords\Domain\Entities\ValueObjects;

use Exception;

class VoucherType
{
    public const ORDER = 6;

    public readonly string $value;

    /**
     * @param string $value
     * @throws Exception
     */
    public function __construct(string $value)
    {
        $this->setValue($value);
    }

    /**
     * @param string $value
     * @return self
     * @throws Exception
     */
    public static function make(string $value): self
    {
        return new VoucherType($value);
    }

    /**
     * @param string $value
     * @return void
     * @throws Exception
     */
    private function setValue(string $value): void
    {
        if (
            $value == '91' ||
            $value == '97' ||
            $value == '98'
        ) {
            throw new Exception('no permitido');
        }

        $this->value = $value;
    }

    public function inValues(string ...$values): bool
    {
        return in_array($this->value, $values);
    }
}
