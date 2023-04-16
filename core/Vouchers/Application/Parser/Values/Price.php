<?php

namespace Core\Vouchers\Application\Parser\Values;

use stdClass;
use Core\Vouchers\Application\Parser\Values\Contracts\Arrayable;
use Core\Vouchers\Application\Parser\Values\Helpers\Filler;
use Core\Vouchers\Application\Parser\Values\Traits\IsArrayable;

class Price implements Arrayable
{
    use IsArrayable;

    public readonly Amount $priceAmount;

    public function __construct(
        Amount $priceAmount
    ) {
        $this->priceAmount = $priceAmount;
    }

    public static function hydrate(array|stdClass $from): self
    {
        $obj = (object) $from;
        Filler::withNull($obj, self::class);

        return new self(
            $obj->priceAmount instanceof Amount || is_null($obj->priceAmount)
                ? $obj->priceAmount
                : Amount::hydrate($obj->priceAmount),
        );
    }
}
