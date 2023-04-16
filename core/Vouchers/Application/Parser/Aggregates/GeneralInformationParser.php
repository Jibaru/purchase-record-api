<?php

namespace Core\Vouchers\Application\Parser\Aggregates;

use SimpleXMLElement;
use stdClass;
use Core\Vouchers\Application\Parser\Values\Date;
use Core\Vouchers\Application\Parser\Values\ID;
use Core\Vouchers\Application\Parser\Values\Time;

class GeneralInformationParser
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

    public function parse(): stdClass
    {
        $invoice = new stdClass();

        $invoice->ublVersionID = new ID((string) $this->cbc->UBLVersionID);
        $invoice->customizationID = new ID((string) $this->cbc->CustomizationID);
        $invoice->ID = new ID((string) $this->cbc->ID); // series-number
        $invoice->issueDate = new Date((string) $this->cbc->IssueDate);
        $invoice->issueTime = new Time((string) $this->cbc->IssueTime);
        $invoice->dueDate = null;

        if ($this->cbc->DueDate) {
            $invoice->dueDate = new Date((string) $this->cbc->DueDate);
        }

        return $invoice;
    }
}
