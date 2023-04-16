<?php

namespace Core\Vouchers\Infrastructure;

use App\Http\Controllers\Controller;
use Core\Vouchers\Application\Parser\InvoiceParserService;
use Core\Vouchers\Application\Parser\Values\Invoice;
use Core\Vouchers\Application\StoreInvoiceUseCase;
use Core\Vouchers\Domain\Entities\Factories\VoucherFactory;
use Core\Vouchers\Infrastructure\Resources\VoucherResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class StoreInvoiceHandler extends Controller
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

        foreach ($files as $file) {
            $xmlContents = file_get_contents($file);
            $vouchers[] = $this->storeInvoiceUseCase->__invoke($xmlContents);
        }

        return response(
            [
                'data' => VoucherResource::collection($vouchers),
            ],
            201
        );
    }
}
