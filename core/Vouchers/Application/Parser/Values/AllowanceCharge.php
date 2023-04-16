<?php

namespace Core\Vouchers\Application\Parser\Values;

use stdClass;
use Core\Vouchers\Application\Parser\Values\Contracts\Arrayable;
use Core\Vouchers\Application\Parser\Values\Traits\IsArrayable;

class AllowanceCharge implements Arrayable
{
    use IsArrayable;

    public readonly ChargeIndicator $chargeIndicator;
    public readonly Amount $amount;

    public function __construct(
        ChargeIndicator $chargeIndicator,
        Amount $amount
    ) {
        $this->chargeIndicator = $chargeIndicator;
        $this->amount = $amount;
    }

    public static function hydrate(array|stdClass $from): self
    {
        $obj = (object) $from;

        return new self(
            $obj->chargeIndicator instanceof ChargeIndicator
                ? $obj->chargeIndicator
                : ChargeIndicator::hydrate($obj->chargeIndicator),
            $obj->amount instanceof Amount
                ? $obj->amount
                : Amount::hydrate($obj->amount),
        );
    }
}
