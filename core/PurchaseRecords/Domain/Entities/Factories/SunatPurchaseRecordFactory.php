<?php

namespace Core\PurchaseRecords\Domain\Entities\Factories;

use Core\PurchaseRecords\Domain\Entities\Builders\PurchaseRecordBuilder;
use Core\PurchaseRecords\Domain\Entities\Builders\SunatPurchaseRecordBuilder;
use Core\Vouchers\Domain\Entities\ValueObjects\VoucherID;

class SunatPurchaseRecordFactory
{
    public function makeBuilder(VoucherID $voucherID): PurchaseRecordBuilder
    {
        return new SunatPurchaseRecordBuilder($voucherID);
    }
}