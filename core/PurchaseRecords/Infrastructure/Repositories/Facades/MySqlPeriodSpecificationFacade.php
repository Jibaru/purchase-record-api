<?php

namespace Core\PurchaseRecords\Infrastructure\Repositories\Facades;

use Core\PurchaseRecords\Domain\Repositories\Specifications\PeriodSpecification;

class MySqlPeriodSpecificationFacade
{
    private PeriodSpecification $periodSpecification;

    public function __construct(PeriodSpecification $periodSpecification)
    {
        $this->periodSpecification = $periodSpecification;
    }

    public function toMySqlCondition(): array
    {
        return [
            'period',
            '=',
            $this->periodSpecification->period->toPurchaseRecordFormat(),
        ];
    }
}
