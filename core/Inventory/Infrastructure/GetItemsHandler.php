<?php

namespace Core\Inventory\Infrastructure;

use Core\Inventory\Application\UseCases\GetItemsUseCase;
use Core\Inventory\Infrastructure\Requests\GetItemsRequest;
use Core\Inventory\Infrastructure\Resources\SimpleItemDtoResource;
use Core\PurchaseRecords\Domain\Entities\ValueObjects\Period;
use Illuminate\Http\Response;

class GetItemsHandler
{
    private GetItemsUseCase $getItemsUseCase;

    public function __construct(GetItemsUseCase $getItemsUseCase)
    {
        $this->getItemsUseCase = $getItemsUseCase;
    }

    public function __invoke(GetItemsRequest $request): Response
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

        [$items, $totalPages] = $this->getItemsUseCase->__invoke($page, $paginate, $period);

        return response([
            'data' => SimpleItemDtoResource::collection($items),
            'page' => $page,
            'paginate' => $paginate,
            'total_pages' => $totalPages,
        ], 200);
    }
}
