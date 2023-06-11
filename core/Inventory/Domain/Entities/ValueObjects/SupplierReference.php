<?php

namespace Core\Inventory\Domain\Entities\ValueObjects;

use Core\PurchaseRecords\Domain\Entities\ValueObjects\SupplierDocumentDenomination;
use Core\PurchaseRecords\Domain\Entities\ValueObjects\SupplierDocumentNumber;
use Core\PurchaseRecords\Domain\Entities\ValueObjects\SupplierDocumentType;
use Core\Shared\Uuid;

class SupplierReference
{
    public readonly string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function make(
        SupplierDocumentType $type,
        SupplierDocumentNumber $number,
        SupplierDocumentDenomination $denomination
    ): self {
        $value = hash('md5', $type->value . '&' . $type->value . '&' . $denomination->value);

        return new SupplierReference($value);
    }
}
