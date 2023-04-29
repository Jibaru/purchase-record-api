<?php

namespace App\Http\Controllers\Vouchers\Voucher;

use Core\Vouchers\Infrastructure\GetVoucherHandler as InfrastructureGetVoucherHandler;
use Illuminate\Http\Response;

class GetVoucherHandler
{
    private InfrastructureGetVoucherHandler $getVoucherHandler;

    public function __construct(InfrastructureGetVoucherHandler $getVoucherHandler)
    {
        $this->getVoucherHandler = $getVoucherHandler;
    }

    public function __invoke(string $voucherID): Response
    {
        return $this->getVoucherHandler->__invoke($voucherID);
    }
}
