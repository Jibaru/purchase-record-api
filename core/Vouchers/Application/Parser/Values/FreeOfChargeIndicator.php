<?php

namespace Core\Vouchers\Application\Parser\Values;

use stdClass;
use Core\Vouchers\Application\Parser\Values\Contracts\Arrayable;
use Core\Vouchers\Application\Parser\Values\Traits\HasBoolValue;
use Core\Vouchers\Application\Parser\Values\Traits\IsArrayable;

class FreeOfChargeIndicator implements Arrayable
{
    use IsArrayable;
    use HasBoolValue;

    public function __construct(
        bool $value
    ) {
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
