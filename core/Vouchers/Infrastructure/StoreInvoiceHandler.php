<?php

namespace Core\Vouchers\Infrastructure;

use Core\Vouchers\Application\StoreInvoiceUseCase;
use Core\Vouchers\Infrastructure\Resources\VoucherResource;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class StoreInvoiceHandler
{
    private StoreInvoiceUseCase $storeInvoiceUseCase;

    public function __construct(StoreInvoiceUseCase $storeInvoiceUseCase)
    {
        $this->storeInvoiceUseCase = $storeInvoiceUseCase;
    }

    public function __invoke(Request $request): Response
    {
        $files = $request->file('file');

        $vouchers = [];

        try {
            foreach ($files as $file) {
                $xmlContents = file_get_contents($file);
                $vouchers[] = $this->storeInvoiceUseCase->__invoke($xmlContents);
            }
        } catch (Exception $exception) {
            return response(
                [
                    'message' => $exception->getMessage(),
                ],
                400
            );
        }

        return response(
            [
                'data' => VoucherResource::collection($vouchers),
            ],
            201
        );
    }
}
