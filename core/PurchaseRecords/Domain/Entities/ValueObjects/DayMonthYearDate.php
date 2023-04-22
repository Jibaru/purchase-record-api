<?php

namespace Core\PurchaseRecords\Domain\Entities\ValueObjects;

use Exception;

class DayMonthYearDate
{
    public readonly int $day;
    public readonly int $month;
    public readonly int $year;

    private function __construct(
        int $day,
        int $month,
        int $year
    ) {
        $this->day = $day;
        $this->month = $month;
        $this->year = $year;
    }

    /**
     * @param string $dayMonthYear
     * @return self
     * @throws Exception
     */
    public static function fromYearMonthDay(string $dayMonthYear): self
    {
        $regex = '/^\d{4}-\d{2}-\d{2}$/';

        if (!preg_match($regex, $dayMonthYear)) {
            throw new Exception('the value does not match with YYYY-MM-DD: ' . $dayMonthYear);
        }

        [$day, $month, $year] = explode('-', $dayMonthYear);

        return new self(
            (int) $day,
            (int) $month,
            (int) $year
        );
    }

    public function betweenPeriods(Period $from, Period $to): bool
    {
        return (
            $this->year <= $to->year &&
            $this->year >= $from->year &&
            $this->month <= $to->month &&
            $this->month >= $from->month
        );
    }

    public function toPurchaseRecordFormat(): string
    {
        return str_pad($this->day, 2, 0) . '/' .
            str_pad($this->month, 2, 0) . '/' .
            $this->year;
    }
}
