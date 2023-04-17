<?php

namespace Core\Vouchers\Domain\Repositories;

use Core\Vouchers\Domain\Entities\ValueObjects\VoucherID;
use Core\Vouchers\Domain\Entities\Voucher;
use Exception;

interface VoucherRepository
{
    public function store(Voucher $voucher): void;

    /**
     * @param  VoucherID $voucherID
     * @return Voucher
     * @throws Exception
     */
    public function getByVoucherID(VoucherID $voucherID): Voucher;

    /**
     * @param int $page
     * @param int|null $perPage
     * @return Voucher[]
     */
    public function getVouchers(int $page, ?int $perPage = null): array;
}
