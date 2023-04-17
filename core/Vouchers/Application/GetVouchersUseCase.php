<?php

namespace Core\Vouchers\Application;

use Core\Vouchers\Domain\Repositories\VoucherRepository;

class GetVouchersUseCase
{
    private VoucherRepository $voucherRepository;

    public function __construct(
        VoucherRepository $voucherRepository
    ) {
        $this->voucherRepository = $voucherRepository;
    }

    /**
     * @param int $page
     * @param int $perPage
     * @return array
     */
    public function __invoke(int $page = 1, int $perPage = 15): array
    {
        return $this->voucherRepository->getVouchers($page, $perPage);
    }
}
