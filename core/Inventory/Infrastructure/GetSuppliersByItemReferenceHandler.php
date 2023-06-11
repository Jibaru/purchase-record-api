<?php

namespace Core\Inventory\Infrastructure;

use Core\Inventory\Application\UseCases\GetSuppliersByItemReferenceUseCase;
use Core\Inventory\Domain\Entities\ValueObjects\SupplierItemReference;
use Core\Inventory\Infrastructure\Resources\SimpleSupplierDtoResource;
use Illuminate\Http\Response;

class GetSuppliersByItemReferenceHandler
{
    private GetSuppliersByItemReferenceUseCase $getSuppliersByItemReferenceUseCase;

    public function __construct(GetSuppliersByItemReferenceUseCase $getSuppliersByItemReferenceUseCase)
    {
        $this->getSuppliersByItemReferenceUseCase = $getSuppliersByItemReferenceUseCase;
    }

    public function __invoke(string $supplierReference): Response
    {
        $items = $this->getSuppliersByItemReferenceUseCase->__invoke(
            new SupplierItemReference($supplierReference)
        );

        return response([
            'data' => SimpleSupplierDtoResource::collection($items)
        ], 200);
    }
}
