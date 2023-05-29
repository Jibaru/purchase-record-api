<?php

namespace Core\PurchaseRecords\Infrastructure;

use Core\PurchaseRecords\Application\UseCases\GetPurchaseRecordsUseCase;
use Core\PurchaseRecords\Domain\Entities\ValueObjects\Period;
use Core\PurchaseRecords\Infrastructure\Requests\GetPurchaseRecordsRequest;
use Core\PurchaseRecords\Infrastructure\Resources\PurchaseRecordDtoResource;
use Illuminate\Http\Response;

class GetPurchaseRecordsHandler
{
    private GetPurchaseRecordsUseCase $getPurchaseRecordsUseCase;

    public function __construct(
        GetPurchaseRecordsUseCase $getPurchaseRecordsUseCase
    ) {
        $this->getPurchaseRecordsUseCase = $getPurchaseRecordsUseCase;
    }

    public function __invoke(GetPurchaseRecordsRequest $request): Response
    {
        $period = null;
        if ($request->filled('period')) {
            $period = new Period(
                $request->input('period.month'),
                $request->input('period.year'),
            );
        }

        $page = $request->input('page', 1);
        $paginate = $request->input('paginate', 15);

        [$purchaseRecordDTOS, $totalPages] = $this->getPurchaseRecordsUseCase->__invoke($page, $paginate, $period);

        return response([
            'data' => PurchaseRecordDtoResource::collection($purchaseRecordDTOS),
            'page' => $page,
            'paginate' => $paginate,
            'total_pages' => $totalPages,
        ], 200);
    }
}
