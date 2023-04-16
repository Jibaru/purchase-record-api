<?php

namespace Core\Vouchers\Application\Parser\Values;

use stdClass;
use Core\Vouchers\Application\Parser\Values\Contracts\Arrayable;
use Core\Vouchers\Application\Parser\Values\Traits\IsArrayable;

class AlternativeConditionPrice implements Arrayable
{
    use IsArrayable;

    public readonly Amount $priceAmount;
    public readonly PriceTypeCode $priceTypeCode;

    public function __construct(
        Amount $priceAmount,
        PriceTypeCode $priceTypeCode
    ) {
        $this->priceAmount = $priceAmount;
        $this->priceTypeCode = $priceTypeCode;
    }

    public static function hydrate(array|stdClass $from): self
    {
        $obj = (object) $from;

        return new self(
            $obj->priceAmount instanceof Amount
                ? $obj->priceAmount
                : Amount::hydrate($obj->priceAmount),
            $obj->priceTypeCode instanceof PriceTypeCode
                ? $obj->priceTypeCode
                : PriceTypeCode::hydrate($obj->priceTypeCode),
        );
    }
}
