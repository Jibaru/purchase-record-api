<?php

namespace Core\Vouchers\Application\Parser\Values;

use Core\Vouchers\Application\Parser\Values\Contracts\Arrayable;
use Core\Vouchers\Application\Parser\Values\Traits\HasListAttributes;
use Core\Vouchers\Application\Parser\Values\Traits\HasListURIAttribute;
use Core\Vouchers\Application\Parser\Values\Traits\HasStringValue;
use Core\Vouchers\Application\Parser\Values\Traits\IsArrayable;
use stdClass;

class PaymentMeansCode implements Arrayable
{
    use IsArrayable;
    use HasListAttributes;
    use HasListURIAttribute;
    use HasStringValue;

    public function __construct(
        string $value,
        string $listAgencyName,
        string $listName,
        string $listURI
    ) {
        $this->value = $value;
        $this->listAgencyName = $listAgencyName;
        $this->listName = $listName;
        $this->listURI = $listURI;
    }

    public static function hydrate(array|stdClass $from): self
    {
        $obj = (object) $from;

        return new self(
            $obj->value,
            $obj->listAgencyName,
            $obj->listName,
            $obj->listURI,
        );
    }
}
