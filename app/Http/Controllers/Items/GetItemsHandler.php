<?php

namespace App\Http\Controllers\Items;

use Core\Inventory\Infrastructure\GetItemsHandler as InfrastructureGetItemsHandler;
use Core\Inventory\Infrastructure\Requests\GetItemsRequest;
use Illuminate\Http\Response;

class GetItemsHandler
{
    private InfrastructureGetItemsHandler $getItemsHandler;

    public function __construct(InfrastructureGetItemsHandler $getItemsHandler)
    {
        $this->getItemsHandler = $getItemsHandler;
    }

    public function __invoke(GetItemsRequest $request): Response
    {
        return $this->getItemsHandler->__invoke($request);
    }
}
