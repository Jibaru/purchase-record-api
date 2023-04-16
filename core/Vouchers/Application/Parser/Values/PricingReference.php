<?php

namespace Core\Vouchers\Application\Parser\Values;

use stdClass;
use Core\Vouchers\Application\Parser\Values\Contracts\Arrayable;
use Core\Vouchers\Application\Parser\Values\Helpers\Filler;
use Core\Vouchers\Application\Parser\Values\Traits\IsArrayable;

class PricingReference implements Arrayable
{
    use IsArrayable;

    public readonly AlternativeConditionPrice $alternativeConditionPrice;

    public function __construct(
        AlternativeConditionPrice $alternativeConditionPrice
    ) {
        $this->alternativeConditionPrice = $alternativeConditionPrice;
    }

    public static function hydrate(array|stdClass $from): self
    {
        $obj = (object) $from;
        Filler::withNull($obj, self::class);

        return new self(
            $obj->alternativeConditionPrice instanceof AlternativeConditionPrice || is_null($obj->alternativeConditionPrice)
                ? $obj->alternativeConditionPrice
                : AlternativeConditionPrice::hydrate($obj->alternativeConditionPrice),
        );
    }
}
