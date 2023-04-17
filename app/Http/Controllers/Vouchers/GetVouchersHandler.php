<?php

namespace App\Http\Controllers\Vouchers;

use Core\Vouchers\Infrastructure\GetVouchersHandler as InfrastructureGetVouchersHandler;
use Core\Vouchers\Infrastructure\Requests\GetVouchersRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class GetVouchersHandler
{
    private InfrastructureGetVouchersHandler $getVouchersHandler;

    public function __construct(InfrastructureGetVouchersHandler $getVouchersHandler)
    {
        $this->getVouchersHandler = $getVouchersHandler;
    }

    public function __invoke(GetVouchersRequest $request): Response
    {
        return $this->getVouchersHandler->__invoke($request);
    }
}
