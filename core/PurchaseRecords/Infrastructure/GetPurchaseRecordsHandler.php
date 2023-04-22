<?php

namespace Core\PurchaseRecords\Infrastructure;

use Core\PurchaseRecords\Domain\Repositories\PurchaseRecordRepository;
use Core\PurchaseRecords\Infrastructure\Resources\PurchaseRecordDtoResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class GetPurchaseRecordsHandler
{
    private PurchaseRecordRepository $purchaseRecordRepository;

    public function __construct(
        PurchaseRecordRepository $purchaseRecordRepository
    ) {
        $this->purchaseRecordRepository = $purchaseRecordRepository;
    }

    public function __invoke(Request $request): Response
    {
        $purchaseRecordDTOS = $this->purchaseRecordRepository->getPurchaseRecordsRows(
            $request->input('page'),
            $request->input('paginate'),
        );

        return response([
            'data' => PurchaseRecordDtoResource::collection($purchaseRecordDTOS),
        ], 200);
    }
}
