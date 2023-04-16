<?php

namespace Core\Vouchers\Application\Parser\Values;

use stdClass;
use Core\Vouchers\Application\Parser\Values\Contracts\Arrayable;
use Core\Vouchers\Application\Parser\Values\Traits\IsArrayable;

class TaxSubtotal implements Arrayable
{
    use IsArrayable;

    public readonly Amount $taxableAmount;
    public readonly Amount $taxAmount;
    public readonly TaxCategory $taxCategory;

    public function __construct(
        Amount $taxableAmount,
        Amount $taxAmount,
        TaxCategory $taxCategory
    ) {
        $this->taxableAmount = $taxableAmount;
        $this->taxAmount = $taxAmount;
        $this->taxCategory = $taxCategory;
    }

    public static function hydrate(array|stdClass $from): self
    {
        $obj = (object) $from;

        return new self(
            $obj->taxableAmount instanceof Amount
                ? $obj->taxableAmount
                : Amount::hydrate($obj->taxableAmount),
            $obj->taxAmount instanceof Amount
                ? $obj->taxAmount
                : Amount::hydrate($obj->taxAmount),
            $obj->taxCategory instanceof TaxCategory
                ? $obj->taxCategory
                : TaxCategory::hydrate($obj->taxCategory),
        );
    }
}
