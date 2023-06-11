<?php

namespace App\Http\Controllers\Suppliers\SupplierReference;

use Core\Inventory\Infrastructure\GetItemsBySupplierReferenceHandler as InfrastructureGetItemsBySupplierReferenceHandler;
use Illuminate\Http\Response;

class GetItemsBySupplierReferenceHandler
{
    private InfrastructureGetItemsBySupplierReferenceHandler $getItemsBySupplierReferenceHandler;

    public function __construct(InfrastructureGetItemsBySupplierReferenceHandler $getItemsBySupplierReferenceHandler)
    {
        $this->getItemsBySupplierReferenceHandler = $getItemsBySupplierReferenceHandler;
    }

    public function __invoke(string $supplierReference): Response
    {
        return $this->getItemsBySupplierReferenceHandler->__invoke($supplierReference);
    }
}
