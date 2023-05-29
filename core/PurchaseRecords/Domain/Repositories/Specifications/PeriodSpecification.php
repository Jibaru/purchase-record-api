<?php

namespace Core\PurchaseRecords\Domain\Repositories\Specifications;

use Core\PurchaseRecords\Domain\Entities\PurchaseRecord;
use Core\PurchaseRecords\Domain\Entities\ValueObjects\Period;

class PeriodSpecification implements Specification
{
    public readonly Period $period;

    public function __construct(Period $period)
    {
        $this->period = $period;
    }
}
