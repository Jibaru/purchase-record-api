<?php

namespace Core\Inventory\Application\UseCases;

use Core\Inventory\Domain\Repositories\SupplierItemRepository;
use Core\PurchaseRecords\Domain\Entities\ValueObjects\Period;
use Core\PurchaseRecords\Domain\Repositories\Specifications\PeriodSpecification;

class GetItemsUseCase
{
    private SupplierItemRepository $supplierItemRepository;

    public function __construct(SupplierItemRepository $supplierItemRepository)
    {
        $this->supplierItemRepository = $supplierItemRepository;
    }

    public function __invoke(int $page, int $paginate, ?Period $period = null): array
    {
        $periodSpecification = null;

        if ($period) {
            $periodSpecification = new PeriodSpecification($period);
        }

        $items = $this->supplierItemRepository->getSimpleItemRows(
            $page,
            $paginate,
            $periodSpecification,
        );

        $totalPages = $this->supplierItemRepository->getTotalPages($paginate, $periodSpecification);

        return [
            $items,
            $totalPages
        ];
    }
}
