<?php

namespace Core\Vouchers\Application\Parser\Values;

use Core\Vouchers\Application\Parser\Values\Helpers\Filler;
use stdClass;
use Core\Vouchers\Application\Parser\Values\Contracts\Arrayable;
use Core\Vouchers\Application\Parser\Values\Traits\IsArrayable;

class Item implements Arrayable
{
    use IsArrayable;

    public readonly Description $description;
    public readonly ?SellersItemIdentification $sellersItemIdentification;

    public function __construct(
        Description $description,
        ?SellersItemIdentification $sellersItemIdentification
    ) {
        $this->description = $description;
        $this->sellersItemIdentification = $sellersItemIdentification;
    }

    public static function hydrate(array|stdClass $from): self
    {
        $obj = (object) $from;
        Filler::withNull($obj, self::class);

        return new self(
            $obj->description instanceof Description
                ? $obj->description
                : Description::hydrate($obj->description),
            $obj->sellersItemIdentification instanceof SellersItemIdentification || is_null($obj->sellersItemIdentification)
                ? $obj->sellersItemIdentification
                : SellersItemIdentification::hydrate($obj->sellersItemIdentification),
        );
    }
}
