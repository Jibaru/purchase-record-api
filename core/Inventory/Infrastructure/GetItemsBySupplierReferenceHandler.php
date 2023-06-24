<?php

namespace Core\Inventory\Infrastructure;

use Core\Inventory\Application\UseCases\GetItemsBySupplierReferenceUseCase;
use Core\Inventory\Domain\Entities\ValueObjects\SupplierReference;
use Core\Inventory\Infrastructure\Resources\SimpleItemDtoResource;
use Illuminate\Http\Response;

class GetItemsBySupplierReferenceHandler
{
    private GetItemsBySupplierReferenceUseCase $getItemsBySupplierReferenceUseCase;

    public function __construct(GetItemsBySupplierReferenceUseCase $getItemsBySupplierReferenceUseCase)
    {
        $this->getItemsBySupplierReferenceUseCase = $getItemsBySupplierReferenceUseCase;
    }

    public function __invoke(string $supplierReference): Response
    {
        $items = $this->getItemsBySupplierReferenceUseCase->__invoke(
            new SupplierReference($supplierReference)
        );

        return response([
            'data' => SimpleItemDtoResource::collection($items)
        ], 200);
    }
}
