<?php

namespace Core\Vouchers\Application\Parser\Values;

use stdClass;
use Core\Vouchers\Application\Parser\Values\Contracts\Arrayable;
use Core\Vouchers\Application\Parser\Values\Helpers\Filler;
use Core\Vouchers\Application\Parser\Values\Traits\IsArrayable;

class AdditionalDocumentReference implements Arrayable
{
    use IsArrayable;

    public readonly ID $ID;
    public readonly DocumentTypeCode $documentTypeCode;

    public function __construct(
        ID $ID,
        DocumentTypeCode $documentTypeCode
    ) {
        $this->ID = $ID;
        $this->documentTypeCode = $documentTypeCode;
    }

    public static function hydrate(array|stdClass $from): self
    {
        $obj = (object) $from;
        Filler::withNull($obj, self::class);

        return new self(
            $obj->ID instanceof ID
                ? $obj->ID
                : new ID($obj->ID),
            $obj->documentTypeCode instanceof DocumentTypeCode
                ? $obj->documentTypeCode
                : DocumentTypeCode::hydrate($obj->documentTypeCode),
        );
    }
}
