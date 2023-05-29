<?php

namespace Core\Vouchers\Application;

use Core\Vouchers\Application\EventBus\EventBus;
use Core\Vouchers\Application\Events\VoucherCreated;
use Core\Vouchers\Application\Parser\Factories\ParserFactory;
use Core\Vouchers\Domain\Entities\Factories\VoucherFactory;
use Core\Vouchers\Domain\Entities\Voucher;
use Core\Vouchers\Domain\Repositories\VoucherRepository;
use Exception;

class StoreInvoiceUseCase
{
    private VoucherRepository $voucherRepository;
    private VoucherFactory $voucherFactory;
    private ParserFactory $parserFactory;
    private EventBus $eventBus;

    public function __construct(
        VoucherRepository $voucherRepository,
        VoucherFactory $voucherFactory,
        ParserFactory $parserFactory,
        EventBus $eventBus,
    ) {
        $this->voucherRepository = $voucherRepository;
        $this->voucherFactory = $voucherFactory;
        $this->parserFactory = $parserFactory;
        $this->eventBus = $eventBus;
    }

    /**
     * @param string $xmlContents
     * @return Voucher
     * @throws Exception
     */
    public function __invoke(
        string $xmlContents
    ): Voucher {
        $parser = $this->parserFactory->makeInvoiceParser($xmlContents);
        $invoice = $parser->parse();
        $voucher = $this->voucherFactory->makeAsInvoice(json_encode($invoice->toArray()), $xmlContents);

        $this->voucherRepository->store($voucher);

        $this->eventBus->dispatch(new VoucherCreated($voucher));

        return $voucher;
    }
}
