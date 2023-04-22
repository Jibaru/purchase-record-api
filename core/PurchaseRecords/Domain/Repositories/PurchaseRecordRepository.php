<?php

namespace Core\PurchaseRecords\Domain\Repositories;

use Core\PurchaseRecords\Domain\Entities\PurchaseRecord;

interface PurchaseRecordRepository
{
    public function store(PurchaseRecord $purchaseRecord): void;

    public function getPurchaseRecordsRows(int $page, ?int $perPage = null): array;
}
