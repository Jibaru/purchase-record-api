<?php

namespace Core\PurchaseRecords\Domain\Entities\ValueObjects;

use Exception;

class VoucherSeries
{
    public const ORDER = 7;

    public readonly string $value;

    /**
     * @param string $value
     * @throws Exception
     */
    public function __construct(string $value)
    {
        $this->setValue($value);
    }

    /**
     * @param string $value
     * @return void
     * @throws Exception
     */
    private function setValue(string $value): void
    {
        if (strlen($value) != 4) {
            throw new Exception();
        }

        $this->value = $value;
    }

    /**
     * @param string $seriesNumber
     * @return self
     * @throws Exception
     */
    public static function fromSeriesNumber(string $seriesNumber): self
    {
        [$series] = explode('-', $seriesNumber);

        return new self($series);
    }
}
