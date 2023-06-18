<?php

namespace Core\Inventory\Infrastructure;

use Core\Inventory\Application\UseCases\GetSuppliersUseCase;
use Core\Inventory\Infrastructure\Requests\GetSuppliersRequest;
use Core\Inventory\Infrastructure\Resources\SimpleSupplierDtoResource;
use Core\PurchaseRecords\Domain\Entities\ValueObjects\Period;
use Illuminate\Http\Response;

class GetSuppliersHandler
{
    private GetSuppliersUseCase $getSuppliersUseCase;

    public function __construct(GetSuppliersUseCase $getSuppliersUseCase)
    {
        $this->getSuppliersUseCase = $getSuppliersUseCase;
    }

    public function __invoke(GetSuppliersRequest $request): Response
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

        [$items, $totalPages] = $this->getSuppliersUseCase->__invoke($page, $paginate, $period);

        return response([
            'data' => SimpleSupplierDtoResource::collection($items),
            'page' => $page,
            'paginate' => $paginate,
            'total_pages' => $totalPages,
        ], 200);
    }
}
