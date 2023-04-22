<?php

namespace Core\PurchaseRecords\Domain\Entities\ValueObjects;

use Exception;

class VoucherNumber
{
    public const ORDER = 9;

    public readonly string $number;

    /**
     * @param string $number
     * @throws Exception
     */
    public function __construct(string $number)
    {
        if (strlen($number) > 20) {
            throw new Exception('number out of range');
        }

        $this->number = $number;
    }

    /**
     * @param string $seriesNumber
     * @return self
     * @throws Exception
     */
    public static function fromSeriesNumber(string $seriesNumber): self
    {
        [$series, $number] = explode('-', $seriesNumber);

        return new self($number);
    }
}
