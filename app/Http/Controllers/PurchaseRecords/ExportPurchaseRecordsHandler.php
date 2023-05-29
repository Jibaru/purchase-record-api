<?php

namespace App\Http\Controllers\PurchaseRecords;

use Core\PurchaseRecords\Infrastructure\Exportables\PurchaseRecordsExportable;
use Core\PurchaseRecords\Infrastructure\ExportPurchaseRecordsHandler as InfrastructureExportPurchaseRecordsHandler;
use Core\PurchaseRecords\Infrastructure\Requests\ExportPurchaseRecordsRequest;

class ExportPurchaseRecordsHandler
{
    private InfrastructureExportPurchaseRecordsHandler $exportPurchaseRecordsHandler;

    public function __construct(InfrastructureExportPurchaseRecordsHandler $exportPurchaseRecordsHandler)
    {
        $this->exportPurchaseRecordsHandler = $exportPurchaseRecordsHandler;
    }

    public function __invoke(ExportPurchaseRecordsRequest $request): PurchaseRecordsExportable
    {
        return $this->exportPurchaseRecordsHandler->__invoke($request);
    }
}
