<?php

namespace Core\Inventory\Application\UseCases;

use Core\Inventory\Domain\Repositories\SupplierRepository;
use Core\PurchaseRecords\Domain\Entities\ValueObjects\Period;
use Core\PurchaseRecords\Domain\Repositories\Specifications\PeriodSpecification;

class GetSuppliersUseCase
{
    private SupplierRepository $supplierRepository;

    public function __construct(SupplierRepository $supplierRepository)
    {
        $this->supplierRepository = $supplierRepository;
    }

    public function __invoke(int $page, int $paginate, ?Period $period = null): array
    {
        $periodSpecification = null;

        if ($period) {
            $periodSpecification = new PeriodSpecification($period);
        }

        $items = $this->supplierRepository->getSimpleSupplierRows(
            $page,
            $paginate,
            $periodSpecification,
        );

        $totalPages = $this->supplierRepository->getTotalPages($paginate, $periodSpecification);

        return [
            $items,
            $totalPages
        ];
    }
}
