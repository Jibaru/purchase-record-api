<?php

namespace Core\Vouchers\Application\Parser\Aggregates;

use SimpleXMLElement;
use Core\Vouchers\Application\Parser\Values\InvoiceTypeCode;

class InvoiceTypeCodeParser
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

    public function parse(): InvoiceTypeCode
    {
        return InvoiceTypeCode::hydrate(
            [
            'value' => (string) $this->cbc->InvoiceTypeCode,
            'listAgencyName' => (string) $this->cbc->InvoiceTypeCode->attributes()->listAgencyName,
            'listName' => (string) $this->cbc->InvoiceTypeCode->attributes()->listName,
            'listURI' => (string) $this->cbc->InvoiceTypeCode->attributes()->listURI,
            'name' => (string) $this->cbc->InvoiceTypeCode->attributes()->name
            ]
        );
    }
}
