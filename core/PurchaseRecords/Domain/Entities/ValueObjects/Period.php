<?php

namespace Core\PurchaseRecords\Domain\Entities\ValueObjects;

use Exception;

class Period
{
    public const ORDER = 1;

    public readonly int $month;

    public readonly int $year;

    private function __construct(int $month, int $year)
    {
        $this->month = $month;
        $this->year = $year;
    }

    public static function now(): self
    {
        $date = getdate();

        return new self(
            $date['mon'],
            $date['year']
        );
    }

    public function toPurchaseRecordFormat(): string
    {
        $month = str_pad($this->month, 2, '0', STR_PAD_LEFT);

        return $this->year . $month . '00';
    }

    public function addMonths(int $months): self
    {
        $newMonth = (($this->month + $months) % 12);
        $newYear = $this->year;

        if ($newMonth == 0) {
            $newMonth = 12;
        } else {
            $newYear += ceil(($this->month + $months) / 12);
        }

        return new self(
            $newMonth,
            $newYear
        );
    }
}
