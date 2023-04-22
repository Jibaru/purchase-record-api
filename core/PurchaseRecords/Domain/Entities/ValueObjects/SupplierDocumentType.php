<?php

namespace Core\PurchaseRecords\Domain\Entities\ValueObjects;

use Exception;

class SupplierDocumentType
{
    public const ORDER = 11;

    public readonly string $value;

    /**
     * @param string $value
     * @throws Exception
     */
    public function __construct(string $value)
    {
        if (strlen($value) != 1) {
            throw new Exception('document type must have only 1 character');
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
