<?php

namespace Core\Vouchers\Application\Parser\Values;

use stdClass;
use Core\Vouchers\Application\Parser\Values\Contracts\Arrayable;
use Core\Vouchers\Application\Parser\Values\Traits\HasSchemeAttributes;
use Core\Vouchers\Application\Parser\Values\Traits\HasSchemeURIAttribute;
use Core\Vouchers\Application\Parser\Values\Traits\HasStringValue;
use Core\Vouchers\Application\Parser\Values\Traits\IsArrayable;

class PartyIdentificationID implements Arrayable
{
    use IsArrayable;
    use HasStringValue;
    use HasSchemeAttributes;
    use HasSchemeURIAttribute;

    public function __construct(
        string $value,
        string $schemeAgencyName,
        string $schemeID,
        string $schemeName,
        string $schemeURI
    ) {
        $this->value = $value;
        $this->schemeAgencyName = $schemeAgencyName;
        $this->schemeID = $schemeID;
        $this->schemeName = $schemeName;
        $this->schemeURI = $schemeURI;
    }

    public static function hydrate(array|stdClass $from): self
    {
        $obj = (object) $from;

        return new self(
            $obj->value,
            $obj->schemeAgencyName,
            $obj->schemeID,
            $obj->schemeName,
            $obj->schemeURI,
        );
    }
}
