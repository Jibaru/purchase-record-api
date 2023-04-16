<?php

namespace Core\Vouchers\Infrastructure\Repositories;

use Core\Vouchers\Domain\Entities\ValueObjects\VoucherID;
use Core\Vouchers\Domain\Entities\Voucher;
use Core\Vouchers\Domain\Repositories\VoucherRepository;
use Exception;
use Illuminate\Support\Arr;

class MemoryVoucherRepository implements VoucherRepository
{
    /**
     * @var Voucher[]
     */
    private array $vouchers = [];

    public function store(Voucher $voucher): void
    {
        $this->vouchers[$voucher->id->value] = $voucher;
    }

    /**
     * @param  VoucherID $voucherID
     * @return Voucher
     * @throws Exception
     */
    public function getByVoucherID(VoucherID $voucherID): Voucher
    {
        if (!Arr::has($this->vouchers, $voucherID->value)) {
            throw new Exception('Voucher not found');
        }

        return $this->vouchers[$voucherID->value];
    }
}
