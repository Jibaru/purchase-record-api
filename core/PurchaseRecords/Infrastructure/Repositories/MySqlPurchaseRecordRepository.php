<?php

namespace Core\PurchaseRecords\Infrastructure\Repositories;

use Core\PurchaseRecords\Domain\Dtos\PurchaseRecordDTO;
use Core\PurchaseRecords\Domain\Entities\PurchaseRecord;
use Core\PurchaseRecords\Domain\Repositories\PurchaseRecordRepository;
use Core\PurchaseRecords\Domain\Repositories\Specifications\PeriodSpecification;
use Core\PurchaseRecords\Domain\Repositories\Specifications\Specification;
use Core\PurchaseRecords\Infrastructure\Repositories\Facades\MySqlPeriodSpecificationFacade;
use Illuminate\Support\Facades\DB;

class MySqlPurchaseRecordRepository implements PurchaseRecordRepository
{
    public function store(PurchaseRecord $purchaseRecord): void
    {
        DB::table('purchase_records')
            ->insert($purchaseRecord->toArray());
    }

    public function getPurchaseRecordsRows(int $page, ?int $perPage = null, ?Specification $specification = null): array
    {
        $builder = DB::table('purchase_records')
            ->orderByDesc('created_at');

        if ($specification instanceof PeriodSpecification) {
            [$field, $op, $value] = (new MySqlPeriodSpecificationFacade($specification))->toMySqlCondition();
            $builder->where($field, $op, $value);
        }

        if (is_null($perPage)) {
            return $builder
                ->get()
                ->reduce(function (array &$purchaseRecords, $record) {
                    $purchaseRecords[] = PurchaseRecordDTO::hydrate($record);
                    return $purchaseRecords;
                }, []);
        }

        $purchaseRecords = $builder
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

    public function getTotalPages(int $perPage, ?Specification $specification = null): int
    {
        $builder = DB::table('purchase_records');

        if ($specification instanceof PeriodSpecification) {
            [$field, $op, $value] = (new MySqlPeriodSpecificationFacade($specification))->toMySqlCondition();
            $builder->where($field, $op, $value);
        }

        $total = $builder->count('id');
        return ceil($total / $perPage);
    }
}
