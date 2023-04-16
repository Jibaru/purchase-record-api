<?php

namespace Core\Vouchers\Application\Parser\Values;

use stdClass;
use Core\Vouchers\Application\Parser\Values\Contracts\Arrayable;
use Core\Vouchers\Application\Parser\Values\Traits\HasListAttributes;
use Core\Vouchers\Application\Parser\Values\Traits\HasStringValue;
use Core\Vouchers\Application\Parser\Values\Traits\IsArrayable;

class DocumentCurrencyCode implements Arrayable
{
    use IsArrayable;
    use HasStringValue;
    use HasListAttributes;

    public function __construct(
        string $value,
        string $listAgencyName,
        string $listName
    ) {
        $this->value = $value;
        $this->listAgencyName = $listAgencyName;
        $this->listName = $listName;
    }

    public static function hydrate(array|stdClass $from): self
    {
        $obj = (object) $from;

        return new self(
            $obj->value,
            $obj->listAgencyName,
            $obj->listName,
        );
    }
}
