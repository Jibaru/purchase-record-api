<?php

namespace Core\Vouchers\Application\EventBus;

use Core\PurchaseRecords\Application\Listeners\CreatePurchaseRecord;
use Core\Vouchers\Application\Events\VoucherCreated;

class EventBus
{
    public function dispatch(VoucherCreated $event): void
    {
        $listener = new CreatePurchaseRecord();
        $listener->handle($event);
    }
}
