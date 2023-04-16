<?php

namespace Core\Vouchers\Application\Parser\Values;

use stdClass;
use Core\Vouchers\Application\Parser\Values\Contracts\Arrayable;
use Core\Vouchers\Application\Parser\Values\Traits\IsArrayable;

class SellersItemIdentification implements Arrayable
{
    use IsArrayable;

    public readonly ID $ID;

    public function __construct(
        ID $ID
    ) {
        $this->ID = $ID;
    }

    public static function hydrate(array|stdClass $from): self
    {
        $obj = (object) $from;

        return new self(
            $obj->ID instanceof ID
                ? $obj->ID
                : ID::hydrate($obj->ID),
        );
    }
}
