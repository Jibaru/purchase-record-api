<?php

namespace Core\PurchaseRecords\Application\UseCases;

use Core\PurchaseRecords\Application\Exportables\CompletePurchaseRecordsExportable;
use Core\PurchaseRecords\Application\Exportables\PurchaseRecordsExportable;
use Core\PurchaseRecords\Domain\Entities\ValueObjects\Period;
use Core\PurchaseRecords\Domain\Repositories\Specifications\PeriodSpecification;

class ExportPurchaseRecordsUseCase
{
    private PurchaseRecordsExportable $purchaseRecordsExportable;

    public function __construct(PurchaseRecordsExportable $purchaseRecordsExportable)
    {
        $this->purchaseRecordsExportable = $purchaseRecordsExportable;
    }

    public function __invoke(?Period $period = null): PurchaseRecordsExportable
    {
        if ($period) {
            $this->purchaseRecordsExportable->setSpecification(
                new PeriodSpecification($period)
            );
        }

        return $this->purchaseRecordsExportable;
    }
}
