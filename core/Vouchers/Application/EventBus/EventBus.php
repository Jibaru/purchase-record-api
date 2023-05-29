<?php

namespace Core\Vouchers\Application\EventBus;

use Core\PurchaseRecords\Application\Listeners\CreatePurchaseRecord;
use Core\PurchaseRecords\Application\UseCases\CreatePurchaseRecordUseCase;
use Core\Vouchers\Application\Events\VoucherCreated;
use Exception;

class EventBus
{
    /**
     * @param VoucherCreated $event
     * @return void
     * @throws Exception
     */
    public function dispatch(VoucherCreated $event): void
    {
        $listener = new CreatePurchaseRecord(
            app(CreatePurchaseRecordUseCase::class)
        );
        $listener->handle($event);
    }
}
