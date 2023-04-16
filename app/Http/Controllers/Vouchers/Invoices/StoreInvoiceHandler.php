<?php

namespace App\Http\Controllers\Vouchers\Invoices;

use App\Http\Controllers\Controller;
use Core\Vouchers\Infrastructure\StoreInvoiceHandler as InfrastructureStoreInvoiceHandler;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class StoreInvoiceHandler extends Controller
{
    private InfrastructureStoreInvoiceHandler $handler;

    public function __construct(InfrastructureStoreInvoiceHandler $handler)
    {
        $this->handler = $handler;
    }

    /**
     * @param  Request $request
     * @return Response
     */
    public function __invoke(Request $request): Response
    {
        return $this->handler->__invoke($request);
    }
}
