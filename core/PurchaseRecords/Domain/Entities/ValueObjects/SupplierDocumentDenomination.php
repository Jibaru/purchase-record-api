<?php

namespace Core\PurchaseRecords\Domain\Entities\ValueObjects;

use Exception;

class SupplierDocumentDenomination
{
    public const ORDER = 13;

    public readonly string $value;

    /**
     * @param string $value
     * @throws Exception
     */
    public function __construct(string $value)
    {
        if (strlen($value) > 100) {
            throw new Exception('denomination exceeded max length');
        }

        $this->value = $value;
    }

    /**
     * @param string $value
     * @return self
     * @throws Exception
     */
    public static function make(string $value): self
    {
        return new self($value);
    }
}
