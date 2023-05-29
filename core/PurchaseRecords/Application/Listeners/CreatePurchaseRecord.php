<?php

namespace Core\PurchaseRecords\Application\Listeners;

use Core\PurchaseRecords\Application\UseCases\CreatePurchaseRecordUseCase;
use Core\Vouchers\Application\Events\VoucherCreated;
use Exception;

class CreatePurchaseRecord
{
    private CreatePurchaseRecordUseCase $createPurchaseRecordUseCase;

    public function __construct(CreatePurchaseRecordUseCase $createPurchaseRecordUseCase)
    {
        $this->createPurchaseRecordUseCase = $createPurchaseRecordUseCase;
    }

    /**
     * @param VoucherCreated $event
     * @return void
     * @throws Exception
     */
    public function handle(VoucherCreated $event): void
    {
        $voucher = $event->voucher;

        $this->createPurchaseRecordUseCase->__invoke($voucher);
    }
}
