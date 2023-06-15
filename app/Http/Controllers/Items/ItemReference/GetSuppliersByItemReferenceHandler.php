<?php

namespace App\Http\Controllers\Items\ItemReference;

use Core\Inventory\Infrastructure\GetSuppliersByItemReferenceHandler as InfrastructureGetSuppliersByItemReferenceHandler;
use Illuminate\Http\Response;

class GetSuppliersByItemReferenceHandler
{
    private InfrastructureGetSuppliersByItemReferenceHandler $getSuppliersByItemReferenceHandler;

    public function __construct(InfrastructureGetSuppliersByItemReferenceHandler $getSuppliersByItemReferenceHandler)
    {
        $this->getSuppliersByItemReferenceHandler = $getSuppliersByItemReferenceHandler;
    }

    public function __invoke(string $itemReference): Response
    {
        return $this->getSuppliersByItemReferenceHandler->__invoke($itemReference);
    }
}
