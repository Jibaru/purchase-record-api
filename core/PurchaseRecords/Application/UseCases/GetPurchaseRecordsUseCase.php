<?php

namespace Core\PurchaseRecords\Application\UseCases;

use Core\PurchaseRecords\Domain\Entities\ValueObjects\Period;
use Core\PurchaseRecords\Domain\Repositories\PurchaseRecordRepository;
use Core\PurchaseRecords\Domain\Repositories\Specifications\PeriodSpecification;

class GetPurchaseRecordsUseCase
{
    private PurchaseRecordRepository $purchaseRecordRepository;

    public function __construct(
        PurchaseRecordRepository $purchaseRecordRepository
    ) {
        $this->purchaseRecordRepository = $purchaseRecordRepository;
    }

    public function __invoke(int $page, int $paginate, ?Period $period = null): array
    {
        $periodSpecification = null;

        if ($period) {
            $periodSpecification = new PeriodSpecification($period);
        }

        $purchaseRecordDTOS = $this->purchaseRecordRepository->getPurchaseRecordsRows(
            $page,
            $paginate,
            $periodSpecification,
        );

        $totalPages = $this->purchaseRecordRepository->getTotalPages($paginate);

        return [
            $purchaseRecordDTOS,
            $totalPages
        ];
    }
}
