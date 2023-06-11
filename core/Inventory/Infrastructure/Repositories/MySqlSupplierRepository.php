<?php

namespace Core\Inventory\Infrastructure\Repositories;

use Core\Inventory\Domain\Dtos\SimpleSupplierDto;
use Core\Inventory\Domain\Entities\Supplier;
use Core\Inventory\Domain\Entities\ValueObjects\SupplierItemReference;
use Core\Inventory\Domain\Repositories\SupplierRepository;
use Core\PurchaseRecords\Domain\Repositories\Specifications\Specification;
use Core\PurchaseRecords\Infrastructure\Repositories\Facades\MySqlPeriodSpecificationFacade;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class MySqlSupplierRepository implements SupplierRepository
{

    public function store(Supplier $supplier): void
    {
        DB::table('suppliers')->insert($supplier->toArray());
    }

    private function apply(Builder $builder, ?Specification $specification = null): Builder
    {
        $builder->select([
            'suppliers.reference as reference',
            'suppliers.document_type as type',
            'suppliers.document_number as number',
            'suppliers.document_denomination as denomination',
            DB::raw('sum(supplier_items.quantity) as total_items'),
            DB::raw('sum(supplier_items.unit_price * supplier_items.quantity) as total_price'),
        ])
            ->join(
                'supplier_items',
                'supplier_items.supplier_id',
                '=',
                'suppliers.id'
            )
            ->groupBy([
                'suppliers.document_type',
                'suppliers.document_number',
                'suppliers.document_denomination',
                'suppliers.reference',
            ]);

        if ($specification) {
            $period = new MySqlPeriodSpecificationFacade($specification);
            [$field, $operator, $value] = $period->toMySqlCondition();

            $builder->where($field, $operator, $value);
        }

        return $builder;
    }

    public function getSimpleSupplierRows(int $page, ?int $perPage = null, ?Specification $specification = null): array
    {
        $builder = DB::table('suppliers');

        $this->apply($builder, $specification);

        if (is_null($perPage)) {
            return $builder->get()
                ->map(fn ($item) => new SimpleSupplierDto(
                    $item->reference,
                    $item->type,
                    $item->number,
                    $item->denomination,
                    $item->total_items,
                    $item->total_price,
                ))
                ->toArray();
        }

        $items = $builder
            ->paginate($perPage, '*', 'page', $page)
            ->items();

        return collect($items)->reduce(function (array &$result, $item) {
            $result[] = new SimpleSupplierDto(
                $item->reference,
                $item->type,
                $item->number,
                $item->denomination,
                $item->total_items,
                $item->total_price,
            );
            return $result;
        }, []);
    }

    public function getTotalPages(int $perPage, ?Specification $specification = null): int
    {
        $total = DB::table(function (Builder $builder) use ($specification) {
            $builder->from('suppliers');
            $this->apply($builder, $specification);
        })->count();

        return ceil($total / $perPage);
    }

    /**
     * @param SupplierItemReference $reference
     * @return SimpleSupplierDto[]
     */
    public function getSimpleSupplierRowsByItemReference(SupplierItemReference $reference): array
    {
        $builder = DB::table('suppliers');

        $this->apply($builder);

        return $builder->where('supplier_items.reference', '=', $reference->value)
            ->get()
            ->map(fn ($item) => new SimpleSupplierDto(
                $item->reference,
                $item->type,
                $item->number,
                $item->denomination,
                $item->total_items,
                $item->total_price,
            ))
            ->toArray();
    }
}
