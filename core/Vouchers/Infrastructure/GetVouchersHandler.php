<?php

namespace Core\Vouchers\Infrastructure;

use Core\Vouchers\Application\GetVouchersUseCase;
use Core\Vouchers\Domain\Repositories\VoucherRepository;
use Core\Vouchers\Infrastructure\Requests\GetVouchersRequest;
use Core\Vouchers\Infrastructure\Resources\SimpleVoucherResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class GetVouchersHandler
{
    private VoucherRepository $voucherRepository;

    private GetVouchersUseCase $getVouchersUseCase;

    public function __construct(
        VoucherRepository $voucherRepository,
        GetVouchersUseCase $getVouchersUseCase
    ) {
        $this->voucherRepository = $voucherRepository;
        $this->getVouchersUseCase = $getVouchersUseCase;
    }

    public function __invoke(GetVouchersRequest $request): Response
    {
        $vouchers = $this->getVouchersUseCase->__invoke(
            $request->input('page', 1),
            $request->input('paginate', 15),
        );

        $totalPages = $this->voucherRepository->getTotalPages($request->input('paginate', 15));

        return response([
            'data' => SimpleVoucherResource::collection($vouchers),
            'page' => $request->input('page', 1),
            'paginate' => $request->input('paginate', 15),
            'total_pages' => $totalPages,
        ], 200);
    }
}
