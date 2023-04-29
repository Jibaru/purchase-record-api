<?php

namespace Core\Vouchers\Infrastructure;

use Core\Vouchers\Domain\Entities\ValueObjects\VoucherID;
use Core\Vouchers\Domain\Repositories\VoucherRepository;
use Core\Vouchers\Infrastructure\Resources\DetailedVoucherResource;
use Illuminate\Http\Response;

class GetVoucherHandler
{
    private VoucherRepository $voucherRepository;

    public function __construct(VoucherRepository $voucherRepository)
    {
        $this->voucherRepository = $voucherRepository;
    }

    public function __invoke(string $voucherID): Response
    {
        try {
            $voucher = $this->voucherRepository->getByVoucherID(new VoucherID($voucherID));
        } catch (\Exception $exception) {
            return response([
                'message' => 'No encontrado',
            ], 404);
        }

        return response([
            'data' => DetailedVoucherResource::make($voucher),
        ], 200);
    }
}
