<?php

namespace App\Http\Controllers\PurchaseRecords;

use Core\PurchaseRecords\Infrastructure\GetPurchaseRecordsHandler as InfrastructureGetPurchaseRecordsHandler;
use Core\PurchaseRecords\Infrastructure\Requests\GetPurchaseRecordsRequest;
use Illuminate\Http\Response;

class GetPurchaseRecordsHandler
{
    private InfrastructureGetPurchaseRecordsHandler $getPurchaseRecordsHandler;

    public function __construct(InfrastructureGetPurchaseRecordsHandler $getPurchaseRecordsHandler)
    {
        $this->getPurchaseRecordsHandler = $getPurchaseRecordsHandler;
    }

    public function __invoke(GetPurchaseRecordsRequest $request): Response
    {
        return $this->getPurchaseRecordsHandler->__invoke($request);
    }
}
