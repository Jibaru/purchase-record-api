<?php

namespace Core\Vouchers\Application\Parser\Values;

use stdClass;
use Core\Vouchers\Application\Parser\Values\Contracts\Arrayable;
use Core\Vouchers\Application\Parser\Values\Helpers\Filler;
use Core\Vouchers\Application\Parser\Values\Traits\IsArrayable;

class TaxCategory implements Arrayable
{
    use IsArrayable;

    public readonly ?TaxScheme $taxScheme;
    public readonly ?TaxCategoryID $ID;
    public readonly ?Percent $percent;
    public readonly ?TierRange $tierRange;
    public readonly ?TaxExemptionReasonCode $taxExemptionReasonCode;

    public function __construct(
        ?TaxScheme $taxScheme,
        ?TaxCategoryID $ID,
        ?Percent $percent,
        ?TierRange $tierRange,
        ?TaxExemptionReasonCode $taxExemptionReasonCode
    ) {
        $this->taxScheme = $taxScheme;
        $this->ID = $ID;
        $this->percent = $percent;
        $this->tierRange = $tierRange;
        $this->taxExemptionReasonCode = $taxExemptionReasonCode;
    }

    public static function hydrate(array|stdClass $from): self
    {
        $obj = (object) $from;
        Filler::withNull($obj, self::class);

        return new self(
            $obj->taxScheme instanceof TaxScheme || is_null($obj->taxScheme)
                ? $obj->taxScheme
                : TaxScheme::hydrate($obj->taxScheme),
            $obj->ID instanceof TaxCategoryID || is_null($obj->ID)
                ? $obj->ID
                : TaxCategoryID::hydrate($obj->ID),
            $obj->percent instanceof Percent || is_null($obj->percent)
                ? $obj->percent
                : Percent::hydrate($obj->percent),
            $obj->tierRange instanceof TierRange || is_null($obj->tierRange)
                ? $obj->tierRange
                : TierRange::hydrate($obj->tierRange),
            $obj->taxExemptionReasonCode instanceof TaxExemptionReasonCode || is_null($obj->taxExemptionReasonCode)
                ? $obj->taxExemptionReasonCode
                : TaxExemptionReasonCode::hydrate($obj->taxExemptionReasonCode),
        );
    }
}
