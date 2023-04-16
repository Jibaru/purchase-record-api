<?php

namespace Core\Vouchers\Application\Parser\Values;

use stdClass;
use Core\Vouchers\Application\Parser\Values\Contracts\Arrayable;
use Core\Vouchers\Application\Parser\Values\Traits\HasStringValue;
use Core\Vouchers\Application\Parser\Values\Traits\IsArrayable;

class CountryIdentificationCode implements Arrayable
{
    use IsArrayable;
    use HasStringValue;

    public function __construct(string $value)
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
