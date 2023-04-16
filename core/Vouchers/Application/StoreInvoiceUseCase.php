<?php

namespace Core\Vouchers\Application;

use Core\Vouchers\Application\Parser\Factories\ParserFactory;
use Core\Vouchers\Domain\Entities\Factories\VoucherFactory;
use Core\Vouchers\Domain\Entities\Voucher;
use Core\Vouchers\Domain\Repositories\VoucherRepository;

class StoreInvoiceUseCase
{
    private VoucherRepository $voucherRepository;
    private VoucherFactory $voucherFactory;
    private ParserFactory $parserFactory;

    public function __construct(
        VoucherRepository $voucherRepository,
        VoucherFactory $voucherFactory,
        ParserFactory $parserFactory
    ) {
        $this->voucherRepository = $voucherRepository;
        $this->voucherFactory = $voucherFactory;
        $this->parserFactory = $parserFactory;
    }

    public function __invoke(
        string $xmlContents
    ): Voucher {
        $parser = $this->parserFactory->makeInvoiceParser($xmlContents);
        $invoice = $parser->parse();
        $voucher = $this->voucherFactory->makeAsInvoice(json_encode($invoice->toArray()));

        $this->voucherRepository->store($voucher);

        return $voucher;
    }
}
