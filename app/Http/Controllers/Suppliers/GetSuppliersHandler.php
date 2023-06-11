<?php

namespace App\Http\Controllers\Suppliers;

use Core\Inventory\Infrastructure\GetSuppliersHandler as InfrastructureGetSuppliersHandler;
use Core\Inventory\Infrastructure\Requests\GetSuppliersRequest;
use Illuminate\Http\Response;

class GetSuppliersHandler
{
    private InfrastructureGetSuppliersHandler $getSuppliersHandler;

    public function __construct(InfrastructureGetSuppliersHandler $getSuppliersHandler)
    {
        $this->getSuppliersHandler = $getSuppliersHandler;
    }

    public function __invoke(GetSuppliersRequest $request): Response
    {
        return $this->getSuppliersHandler->__invoke($request);
    }
}
