<?php

namespace Core\Inventory\Domain\Entities;

use Carbon\Carbon;
use Core\Inventory\Domain\Entities\ValueObjects\InventoryPrice;
use Core\Inventory\Domain\Entities\ValueObjects\InventoryQuantity;
use Core\Inventory\Domain\Entities\ValueObjects\SupplierID;
use Core\Inventory\Domain\Entities\ValueObjects\SupplierItemCode;
use Core\Inventory\Domain\Entities\ValueObjects\SupplierItemDescription;
use Core\Inventory\Domain\Entities\ValueObjects\SupplierItemID;
use Core\Inventory\Domain\Entities\ValueObjects\SupplierItemReference;

class SupplierItem
{
    private SupplierItemID $id;
    private SupplierItemReference $reference;
    private SupplierItemCode $code;
    private SupplierItemDescription $description;
    private InventoryQuantity $quantity;
    private InventoryPrice $unitPrice;
    private SupplierID $supplierID;
    private Carbon $createdAt;
    private Carbon $updatedAt;

    public function __construct(
        SupplierItemID $id,
        SupplierItemReference $reference,
        SupplierItemCode $code,
        SupplierItemDescription $description,
        InventoryQuantity $quantity,
        InventoryPrice $unitPrice,
        SupplierID $supplierID,
        Carbon $createdAt,
        Carbon $updatedAt
    ) {
        $this->id = $id;
        $this->reference = $reference;
        $this->code = $code;
        $this->description = $description;
        $this->quantity = $quantity;
        $this->unitPrice = $unitPrice;
        $this->supplierID = $supplierID;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id->value,
            'reference' => $this->reference->value,
            'code' => $this->code->value,
            'description' => $this->description->value,
            'quantity' => $this->quantity->value,
            'unit_price' => $this->unitPrice->value,
            'supplier_id' => $this->supplierID->value,
            'created_at' => $this->createdAt->toDateTimeString(),
            'updated_at' => $this->updatedAt->toDateTimeString(),
        ];
    }
}
