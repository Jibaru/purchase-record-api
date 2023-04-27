<?php

namespace Core\Vouchers\Application\Parser\Values;

use Core\Vouchers\Application\Parser\Values\Contracts\Arrayable;
use Core\Vouchers\Application\Parser\Values\Traits\HasFloatValue;
use Core\Vouchers\Application\Parser\Values\Traits\IsArrayable;
use stdClass;

class PaymentPercent implements Arrayable
{
    use IsArrayable;
    use HasFloatValue;

    public function __construct(float $value)
    {
        $this->value = $value;
    }

    public static function hydrate(array|stdClass $from): self
    {
        $obj = (object) $from;

        return new self(
            $obj->value,
        );
    }
}
