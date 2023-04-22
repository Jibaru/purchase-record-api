<?php

namespace Core\PurchaseRecords\Infrastructure\Repositories;

use Core\PurchaseRecords\Domain\Dtos\PurchaseRecordDTO;
use Core\PurchaseRecords\Domain\Entities\PurchaseRecord;
use Core\PurchaseRecords\Domain\Repositories\PurchaseRecordRepository;
use Illuminate\Support\Facades\DB;

class MySqlPurchaseRecordRepository implements PurchaseRecordRepository
{
    public function store(PurchaseRecord $purchaseRecord): void
    {
        DB::table('purchase_records')
            ->insert($purchaseRecord->toArray());
    }

    public function getPurchaseRecordsRows(int $page, ?int $perPage = null): array
    {
        if (is_null($perPage)) {
            return DB::table('purchase_records')
                ->orderByDesc('created_at')
                ->get()
                ->reduce(function (array &$purchaseRecords, $record) {
                    $purchaseRecords[] = PurchaseRecordDTO::hydrate($record);
                    return $purchaseRecords;
                }, []);
        }

        $purchaseRecords = DB::table('purchase_records')
            ->orderByDesc('created_at')
            ->paginate($perPage, '*', 'page', $page)
            ->items();

        if (empty($purchaseRecords)) {
            return [];
        }

        return collect($purchaseRecords)->reduce(function (array &$purchaseRecords, $record) {
            $purchaseRecords[] = PurchaseRecordDTO::hydrate($record);
            return $purchaseRecords;
        }, []);
    }
}
