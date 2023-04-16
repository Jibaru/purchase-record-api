<?php

namespace Core\Vouchers\Application\Parser\Aggregates;

use SimpleXMLElement;
use Core\Vouchers\Application\Parser\Values\DocumentCurrencyCode;

class DocumentCurrencyCodeParser
{
    private SimpleXMLElement $xml;
    private SimpleXMLElement $cbc;
    private SimpleXMLElement $cac;

    public function __construct(
        SimpleXMLElement $xml,
        SimpleXMLElement $cbc,
        SimpleXMLElement $cac
    ) {
        $this->xml = $xml;
        $this->cbc = $cbc;
        $this->cac = $cac;
    }

    public function parse(): DocumentCurrencyCode
    {
        return DocumentCurrencyCode::hydrate(
            [
            'value' => (string) $this->cbc->DocumentCurrencyCode,
            'listID' => (string) $this->cbc->DocumentCurrencyCode->attributes()->listID,
            'listName' => (string) $this->cbc->DocumentCurrencyCode->attributes()->listName,
            'listAgencyName' => (string) $this->cbc->DocumentCurrencyCode->attributes()->listAgencyName
            ]
        );
    }
}
