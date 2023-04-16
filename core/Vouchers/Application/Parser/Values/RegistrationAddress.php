<?php

namespace Core\Vouchers\Application\Parser\Values;

use stdClass;
use Core\Vouchers\Application\Parser\Values\Contracts\Arrayable;
use Core\Vouchers\Application\Parser\Values\Helpers\Filler;
use Core\Vouchers\Application\Parser\Values\Traits\IsArrayable;

class RegistrationAddress implements Arrayable
{
    use IsArrayable;

    public readonly AddressLine $addressLine;
    public readonly ?AddressTypeCode $addressTypeCode;
    public readonly ?CitySubdivisionName $citySubdivisionName;
    public readonly ?CityName $cityName;
    public readonly ?CountrySubEntity $countrySubEntity;
    public readonly ?CountrySubEntityCode $countrySubEntityCode;
    public readonly ?District $district;
    public readonly ?CountryIdentificationCode $countryIdentificationCode;

    public function __construct(
        AddressLine $addressLine,
        ?AddressTypeCode $addressTypeCode,
        ?CityName $cityName,
        ?CitySubdivisionName $citySubdivisionName,
        ?CountrySubEntity $countrySubEntity,
        ?CountrySubEntityCode $countrySubEntityCode,
        ?CountryIdentificationCode $countryIdentificationCode,
        ?District $district
    ) {
        $this->addressLine = $addressLine;
        $this->addressTypeCode = $addressTypeCode;
        $this->cityName = $cityName;
        $this->citySubdivisionName = $citySubdivisionName;
        $this->countrySubEntity = $countrySubEntity;
        $this->countrySubEntityCode = $countrySubEntityCode;
        $this->countryIdentificationCode = $countryIdentificationCode;
        $this->district = $district;
    }

    public static function hydrate(array|stdClass $from): self
    {
        $obj = (object) $from;
        Filler::withNull($obj, self::class);

        return new self(
            $obj->addressLine instanceof AddressLine
                ? $obj->addressLine
                : AddressLine::hydrate($obj->addressLine),
            $obj->addressTypeCode instanceof AddressTypeCode || is_null($obj->addressTypeCode)
                ? $obj->addressTypeCode
                : AddressTypeCode::hydrate($obj->addressTypeCode),
            $obj->cityName instanceof CityName || is_null($obj->cityName)
                ? $obj->cityName
                : CityName::hydrate($obj->cityName),
            $obj->citySubdivisionName instanceof CitySubdivisionName || is_null($obj->citySubdivisionName)
                ? $obj->citySubdivisionName
                : CitySubdivisionName::hydrate($obj->citySubdivisionName),
            $obj->countrySubEntity instanceof CountrySubEntity || is_null($obj->countrySubEntity)
                ? $obj->countrySubEntity
                : CountrySubEntity::hydrate($obj->countrySubEntity),
            $obj->countrySubEntityCode instanceof CountrySubEntityCode || is_null($obj->countrySubEntityCode)
                ? $obj->countrySubEntityCode
                : CountrySubEntityCode::hydrate($obj->countrySubEntityCode),
            $obj->countryIdentificationCode instanceof CountryIdentificationCode || is_null($obj->countryIdentificationCode)
                ? $obj->countryIdentificationCode
                : CountryIdentificationCode::hydrate($obj->countryIdentificationCode),
            $obj->district instanceof District || is_null($obj->district)
                ? $obj->district
                : District::hydrate($obj->district),
        );
    }
}
