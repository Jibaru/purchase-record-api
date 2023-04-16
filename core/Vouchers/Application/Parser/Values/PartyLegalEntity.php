<?php

namespace Core\Vouchers\Application\Parser\Values;

use stdClass;
use Core\Vouchers\Application\Parser\Values\Contracts\Arrayable;
use Core\Vouchers\Application\Parser\Values\Helpers\Filler;
use Core\Vouchers\Application\Parser\Values\Traits\IsArrayable;

class PartyLegalEntity implements Arrayable
{
    use IsArrayable;

    public readonly ?RegistrationName $registrationName;
    public readonly ?RegistrationAddress $registrationAddress;

    public function __construct(
        ?RegistrationName $registrationName,
        ?RegistrationAddress $registrationAddress
    ) {
        $this->registrationName = $registrationName;
        $this->registrationAddress = $registrationAddress;
    }

    public static function hydrate(array|stdClass $from): self
    {
        $obj = (object) $from;
        Filler::withNull($obj, self::class);

        return new self(
            $obj->registrationName instanceof RegistrationName || is_null($obj->registrationName)
                ? $obj->registrationName
                : RegistrationName::hydrate($obj->registrationName),
            $obj->registrationAddress instanceof RegistrationAddress || is_null($obj->registrationAddress)
                ? $obj->registrationAddress
                : RegistrationAddress::hydrate($obj->registrationAddress),
        );
    }
}
