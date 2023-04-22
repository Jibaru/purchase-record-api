<?php

namespace Core\Vouchers\Domain\Entities\Factories;

use Carbon\Carbon;
use Core\Vouchers\Domain\Entities\ValueObjects\VoucherContent;
use Core\Vouchers\Domain\Entities\ValueObjects\VoucherID;
use Core\Vouchers\Domain\Entities\ValueObjects\VoucherType;
use Core\Vouchers\Domain\Entities\ValueObjects\VoucherXMLContent;
use Core\Vouchers\Domain\Entities\Voucher;

class VoucherFactory
{
    public function makeAsInvoice(
        string $content,
        string $xmlContent
    ): Voucher {
        return new Voucher(
            VoucherID::empty(),
            VoucherType::invoice(),
            VoucherContent::fromJson($content),
            new VoucherXMLContent($xmlContent),
            Carbon::now(),
            Carbon::now()
        );
    }
}
