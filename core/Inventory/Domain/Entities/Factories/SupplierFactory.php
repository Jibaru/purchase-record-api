<?php

namespace Core\Inventory\Domain\Entities\Factories;

use Carbon\Carbon;
use Core\Inventory\Domain\Entities\Supplier;
use Core\Inventory\Domain\Entities\SupplierItem;
use Core\Inventory\Domain\Entities\ValueObjects\InventoryPrice;
use Core\Inventory\Domain\Entities\ValueObjects\InventoryQuantity;
use Core\Inventory\Domain\Entities\ValueObjects\SupplierID;
use Core\Inventory\Domain\Entities\ValueObjects\SupplierItemCode;
use Core\Inventory\Domain\Entities\ValueObjects\SupplierItemDescription;
use Core\Inventory\Domain\Entities\ValueObjects\SupplierItemID;
use Core\Inventory\Domain\Entities\ValueObjects\SupplierItemReference;
use Core\Inventory\Domain\Entities\ValueObjects\SupplierReference;
use Core\PurchaseRecords\Domain\Entities\PurchaseRecord;
use Core\Vouchers\Domain\Entities\Voucher;

class SupplierFactory
{
    public function buildFromPurchaseRecord(PurchaseRecord $purchaseRecord): Supplier
    {
        [$type, $denomination, $number] = $purchaseRecord->supplierInformation();

        return new Supplier(
            SupplierID::empty(),
            SupplierReference::make($type, $number, $denomination),
            $type,
            $denomination,
            $number,
            $purchaseRecord->period(),
            $purchaseRecord->id(),
            Carbon::now(),
            Carbon::now()
        );
    }

    /**
     * @param Voucher $voucher
     * @param SupplierID $supplierID
     * @return SupplierItem[]
     */
    public function buildItemsFromVoucher(Voucher $voucher, SupplierID $supplierID): array
    {
        $content = $voucher->parseContent();

        $items = [];
        foreach ($content->invoiceLines as $line) {
            $code = new SupplierItemCode($line?->item?->sellersItemIdentification?->ID->value);
            $description = new SupplierItemDescription($line?->item?->description?->value);

            $items[] = new SupplierItem(
                SupplierItemID::empty(),
                SupplierItemReference::make($code, $description),
                $code,
                $description,
                new InventoryQuantity($line->invoicedQuantity->value),
                new InventoryPrice($line?->price?->priceAmount?->value),
                $supplierID,
                Carbon::now(),
                Carbon::now(),
            );
        }

        return $items;
    }
}
