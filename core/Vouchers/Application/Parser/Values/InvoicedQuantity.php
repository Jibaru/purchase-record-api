<?php

namespace Core\Vouchers\Application\Parser\Values;

use stdClass;
use Core\Vouchers\Application\Parser\Values\Contracts\Arrayable;
use Core\Vouchers\Application\Parser\Values\Traits\HasFloatValue;
use Core\Vouchers\Application\Parser\Values\Traits\HasUnitCodeAttributes;
use Core\Vouchers\Application\Parser\Values\Traits\IsArrayable;

class InvoicedQuantity implements Arrayable
{
    use IsArrayable;
    use HasFloatValue;
    use HasUnitCodeAttributes;

    public function __construct(
        float $value,
        string $unitCode,
        string $unitCodeListAgencyName,
        string $unitCodeListID
    ) {
        $this->value = $value;
        $this->unitCode = $unitCode;
        $this->unitCodeListAgencyName = $unitCodeListAgencyName;
        $this->unitCodeListID = $unitCodeListID;
    }

    public static function hydrate(array|stdClass $from): self
    {
        $obj = (object) $from;

        return new self(
            $obj->value,
            $obj->unitCode,
            $obj->unitCodeListAgencyName,
            $obj->unitCodeListID,
        );
    }
}
