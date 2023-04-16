<?php

namespace Core\Vouchers\Application\Parser\Values;

use stdClass;
use Core\Vouchers\Application\Parser\Values\Contracts\Arrayable;
use Core\Vouchers\Application\Parser\Values\Helpers\Filler;
use Core\Vouchers\Application\Parser\Values\Traits\IsArrayable;

class TaxScheme implements Arrayable
{
    use IsArrayable;

    public readonly TaxSchemeID $ID;
    public readonly TaxSchemeName $name;
    public readonly TaxSchemeTaxTypeCode $taxTypeCode;

    public function __construct(
        TaxSchemeID $ID,
        TaxSchemeName $name,
        TaxSchemeTaxTypeCode $taxTypeCode
    ) {
        $this->ID = $ID;
        $this->name = $name;
        $this->taxTypeCode = $taxTypeCode;
    }

    public static function hydrate(array|stdClass $from): self
    {
        $obj = (object) $from;
        Filler::withNull($obj, self::class);

        return new self(
            $obj->ID instanceof TaxSchemeID
                ? $obj->ID
                : TaxScheme::hydrate($obj->ID),
            $obj->name instanceof TaxSchemeName || is_null($obj->name)
                ? $obj->name
                : TaxSchemeName::hydrate($obj->name),
            $obj->taxTypeCode instanceof TaxSchemeTaxTypeCode || is_null($obj->taxTypeCode)
                ? $obj->taxTypeCode
                : TaxSchemeTaxTypeCode::hydrate($obj->taxTypeCode),
        );
    }
}
