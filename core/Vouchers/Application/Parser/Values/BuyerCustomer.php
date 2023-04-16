<?php

namespace Core\Vouchers\Application\Parser\Values;

use stdClass;
use Core\Vouchers\Application\Parser\Values\Contracts\Arrayable;
use Core\Vouchers\Application\Parser\Values\Helpers\Filler;
use Core\Vouchers\Application\Parser\Values\Traits\IsArrayable;

class BuyerCustomer implements Arrayable
{
    use IsArrayable;

    public readonly ?PartyIdentificationID $partyIdentificationID;
    public readonly ?PartyLegalEntity $partyLegalEntity;

    public function __construct(
        ?PartyIdentificationID $partyIdentificationID,
        ?PartyLegalEntity $partyLegalEntity
    ) {
        $this->partyIdentificationID = $partyIdentificationID;
        $this->partyLegalEntity = $partyLegalEntity;
    }

    public static function hydrate(array|stdClass $from): self
    {
        $obj = (object) $from;
        Filler::withNull($obj, self::class);

        return new self(
            $obj->partyIdentificationID instanceof PartyIdentificationID || is_null($obj->partyIdentificationID)
                ? $obj->partyIdentificationID
                : PartyIdentificationID::hydrate($obj->partyIdentificationID),
            $obj->partyLegalEntity instanceof PartyLegalEntity || is_null($obj->partyLegalEntity)
                ? $obj->partyLegalEntity
                : PartyLegalEntity::hydrate($obj->partyLegalEntity),
        );
    }
}
