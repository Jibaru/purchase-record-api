<?php

namespace Core\PurchaseRecords\Infrastructure;

use Core\PurchaseRecords\Application\UseCases\ExportPurchaseRecordsUseCase;
use Core\PurchaseRecords\Domain\Entities\ValueObjects\Period;
use Core\PurchaseRecords\Infrastructure\Exportables\PurchaseRecordsExportable;
use Core\PurchaseRecords\Infrastructure\Requests\ExportPurchaseRecordsRequest;

class ExportPurchaseRecordsHandler
{
    private ExportPurchaseRecordsUseCase $exportPurchaseRecordsUseCase;

    public function __construct(ExportPurchaseRecordsUseCase $exportPurchaseRecordsUseCase)
    {
        $this->exportPurchaseRecordsUseCase = $exportPurchaseRecordsUseCase;
    }

    public function __invoke(ExportPurchaseRecordsRequest $request): PurchaseRecordsExportable
    {
        $period = null;
        if ($request->filled('period')) {
            $period = new Period(
                $request->input('period.month'),
                $request->input('period.year'),
            );
        }

        return new PurchaseRecordsExportable($this->exportPurchaseRecordsUseCase->__invoke($period));
    }
}
