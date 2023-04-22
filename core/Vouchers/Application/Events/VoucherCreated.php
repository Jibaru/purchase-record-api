<?php

namespace Core\Vouchers\Application\Events;

use Core\Vouchers\Domain\Entities\Voucher;

class VoucherCreated
{
    public readonly Voucher $voucher;

    public function __construct(Voucher $voucher)
    {
        $this->voucher = $voucher;
    }
}
