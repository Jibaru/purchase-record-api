<?php

namespace Core\Inventory\Infrastructure\Repositories;

use Core\Inventory\Domain\Dtos\SimpleItemDto;
use Core\Inventory\Domain\Entities\SupplierItem;
use Core\Inventory\Domain\Entities\ValueObjects\SupplierReference;
use Core\Inventory\Domain\Repositories\SupplierItemRepository;
use Core\PurchaseRecords\Domain\Repositories\Specifications\PeriodSpecification;
use Core\PurchaseRecords\Domain\Repositories\Specifications\Specification;
use Core\PurchaseRecords\Infrastructure\Repositories\Facades\MySqlPeriodSpecificationFacade;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class MySqlSupplierItemRepository implements SupplierItemRepository
{
    public function store(SupplierItem $supplierItem): void
    {
        DB::table('supplier_items')->insert($supplierItem->toArray());
    }

    private function apply(Builder $builder, ?Specification $specification = null): Builder
    {
        $builder->select([
                'supplier_items.reference as reference',
                'supplier_items.code as code',
                'supplier_items.description as description',
                DB::raw('sum(supplier_items.quantity) as total_quantity'),
                DB::raw('avg(supplier_items.unit_price) as unit_price_average'),
            ])
            ->join(
                'suppliers',
                'suppliers.id',
                '=',
                'supplier_items.supplier_id'
            )
            ->groupBy([
                'supplier_items.code',
                'supplier_items.description',
                'supplier_items.reference',
            ]);

        if ($specification) {
            $period = new MySqlPeriodSpecificationFacade($specification);
            [$field, $operator, $value] = $period->toMySqlCondition();

            $builder->where($field, $operator, $value);
        }

        return $builder;
    }

    public function getSimpleItemRows(int $page, ?int $perPage = null, ?Specification $specification = null): array
    {
        $builder = DB::table('supplier_items');

        $this->apply($builder, $specification);

        if (is_null($perPage)) {
            return $builder->get()
                ->map(fn ($item) => new SimpleItemDto(
                    $item->reference,
                    $item->code,
                    $item->description,
                    $item->total_quantity,
                    $item->unit_price_average
                ))
                ->toArray();
        }

        $items = $builder
            ->paginate($perPage, '*', 'page', $page)
            ->items();

        return collect($items)->reduce(function (array &$result, $item) {
            $result[] = new SimpleItemDto(
                $item->reference,
                $item->code,
                $item->description,
                $item->total_quantity,
                $item->unit_price_average
            );
            return $result;
        }, []);
    }

    public function getTotalPages(int $perPage, ?Specification $specification = null): int
    {
        $total = DB::table(function (Builder $builder) use ($specification) {
            $builder->from('supplier_items');
            $this->apply($builder, $specification);
        })->count();

        return ceil($total / $perPage);
    }

    /**
     * @param SupplierReference $reference
     * @return SimpleItemDto[]
     */
    public function getSimpleSupplierItemRowsBySupplierReference(SupplierReference $reference): array
    {
        $builder = DB::table('supplier_items');

        $this->apply($builder);

        return $builder->where('suppliers.reference', '=', $reference->value)
            ->get()
            ->map(fn ($item) => new SimpleItemDto(
                $item->reference,
                $item->code,
                $item->description,
                $item->total_quantity,
                $item->unit_price_average
            ))
            ->toArray();
    }
}
