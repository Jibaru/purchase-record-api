<?php

namespace Core\Vouchers\Infrastructure;

use Core\Vouchers\Application\GetVouchersUseCase;
use Core\Vouchers\Infrastructure\Requests\GetVouchersRequest;
use Core\Vouchers\Infrastructure\Resources\SimpleVoucherResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class GetVouchersHandler
{
    private GetVouchersUseCase $getVouchersUseCase;

    public function __construct(
        GetVouchersUseCase $getVouchersUseCase
    ) {
        $this->getVouchersUseCase = $getVouchersUseCase;
    }

    public function __invoke(GetVouchersRequest $request): Response
    {
        $vouchers = $this->getVouchersUseCase->__invoke(
            $request->input('page'),
            $request->input('paginate'),
        );

        return response([
            'data' => SimpleVoucherResource::collection($vouchers),
            'page' => $request->input('page'),
            'paginate' => $request->input('paginate'),
        ], 200);
    }
}
