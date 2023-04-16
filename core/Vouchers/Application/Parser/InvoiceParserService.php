<?php

namespace Core\Vouchers\Application\Parser;

use Core\Vouchers\Application\Parser\Aggregates\DocumentCurrencyCodeParser;
use Core\Vouchers\Application\Parser\Aggregates\InvoiceTypeCodeParser;
use Core\Vouchers\Application\Parser\Values\AccountingCustomer;
use Core\Vouchers\Application\Parser\Values\AccountingSupplier;
use Core\Vouchers\Application\Parser\Values\AdditionalDocumentReference;
use Core\Vouchers\Application\Parser\Values\AddressLine;
use Core\Vouchers\Application\Parser\Values\AddressTypeCode;
use Core\Vouchers\Application\Parser\Values\AllowanceCharge;
use Core\Vouchers\Application\Parser\Values\AlternativeConditionPrice;
use Core\Vouchers\Application\Parser\Values\Amount;
use Core\Vouchers\Application\Parser\Values\BuyerCustomer;
use Core\Vouchers\Application\Parser\Values\ChargeIndicator;
use Core\Vouchers\Application\Parser\Values\CityName;
use Core\Vouchers\Application\Parser\Values\CitySubdivisionName;
use Core\Vouchers\Application\Parser\Values\CountryIdentificationCode;
use Core\Vouchers\Application\Parser\Values\CountrySubEntity;
use Core\Vouchers\Application\Parser\Values\CountrySubEntityCode;
use Core\Vouchers\Application\Parser\Values\Date;
use Core\Vouchers\Application\Parser\Values\Description;
use Core\Vouchers\Application\Parser\Values\District;
use Core\Vouchers\Application\Parser\Values\DocumentTypeCode;
use Core\Vouchers\Application\Parser\Values\FreeOfChargeIndicator;
use Core\Vouchers\Application\Parser\Values\ID;
use Core\Vouchers\Application\Parser\Values\Invoice;
use Core\Vouchers\Application\Parser\Values\InvoicedQuantity;
use Core\Vouchers\Application\Parser\Values\InvoiceLine;
use Core\Vouchers\Application\Parser\Values\Item;
use Core\Vouchers\Application\Parser\Values\LegalMonetaryTotal;
use Core\Vouchers\Application\Parser\Values\Note;
use Core\Vouchers\Application\Parser\Values\PartyIdentificationID;
use Core\Vouchers\Application\Parser\Values\PartyLegalEntity;
use Core\Vouchers\Application\Parser\Values\PaymentTerm;
use Core\Vouchers\Application\Parser\Values\Percent;
use Core\Vouchers\Application\Parser\Values\Price;
use Core\Vouchers\Application\Parser\Values\PriceTypeCode;
use Core\Vouchers\Application\Parser\Values\PricingReference;
use Core\Vouchers\Application\Parser\Values\RegistrationAddress;
use Core\Vouchers\Application\Parser\Values\RegistrationName;
use Core\Vouchers\Application\Parser\Values\SellersItemIdentification;
use Core\Vouchers\Application\Parser\Values\TaxCategory;
use Core\Vouchers\Application\Parser\Values\TaxCategoryID;
use Core\Vouchers\Application\Parser\Values\TaxExemptionReasonCode;
use Core\Vouchers\Application\Parser\Values\TaxScheme;
use Core\Vouchers\Application\Parser\Values\TaxSchemeID;
use Core\Vouchers\Application\Parser\Values\TaxSchemeName;
use Core\Vouchers\Application\Parser\Values\TaxSchemeTaxTypeCode;
use Core\Vouchers\Application\Parser\Values\TaxSubtotal;
use Core\Vouchers\Application\Parser\Values\TaxTotal;
use Core\Vouchers\Application\Parser\Values\TierRange;
use Core\Vouchers\Application\Parser\Values\Time;
use SimpleXMLElement;
use stdClass;

class InvoiceParserService implements Parser
{
    private SimpleXMLElement $xml;
    private SimpleXMLElement $cbc;
    private SimpleXMLElement $cac;

    public function __construct(string $fileContents)
    {
        $this->xml = new SimpleXMLElement($fileContents);
        $namespaces = $this->xml->getDocNamespaces();

        foreach ($namespaces as $namespace => $value) {
            if (!empty($namespace)) {
                $this->xml->registerXPathNamespace($namespace, $value);
            }
        }

        $this->cbc = $this->xml->children($namespaces['cbc']);
        $this->cac = $this->xml->children($namespaces['cac']);
    }

    public function parse(): Invoice
    {
        $invoice = new stdClass();

        $this->setGeneral($invoice);
        $this->setInvoiceTypeCode($invoice);
        $this->setDocumentCurrencyCode($invoice);
        $this->setNotes($invoice);

        $this->setAdditionalDocumentReferences($invoice);
        $this->setAccountingSupplier($invoice);
        $this->setAccountingCustomer($invoice);
        $this->setBuyerCustomer($invoice);

        $this->setPaymentTerms($invoice);

        $this->setTaxTotal($invoice);
        $this->setLegalMonetaryTotal($invoice);
        $this->setInvoiceLines($invoice);

        return Invoice::hydrate($invoice);
    }

    private function setGeneral(stdClass $invoice): void
    {
        $invoice->ublVersionID = new ID((string) $this->cbc->UBLVersionID);
        $invoice->customizationID = new ID((string) $this->cbc->CustomizationID);
        $invoice->ID = new ID((string) $this->cbc->ID); // series-number
        $invoice->issueDate = new Date((string) $this->cbc->IssueDate);
        $invoice->issueTime = new Time((string) $this->cbc->IssueTime);
        $invoice->dueDate = null;

        if ($this->cbc->DueDate) {
            $invoice->dueDate = new Date((string) $this->cbc->DueDate);
        }
    }

    public function setInvoiceTypeCode(stdClass $invoice): void
    {
        $invoice->invoiceTypeCode = (new InvoiceTypeCodeParser(
            $this->xml,
            $this->cbc,
            $this->cac
        ))->parse();
    }

    private function setDocumentCurrencyCode(stdClass $invoice): void
    {
        $invoice->documentCurrencyCode = (new DocumentCurrencyCodeParser(
            $this->xml,
            $this->cbc,
            $this->cac
        ))->parse();
    }

    private function setNotes(stdClass $invoice): void
    {
        $invoice->notes = [];

        foreach ($this->cbc->Note as $note) {
            $invoice->notes[] = Note::hydrate(
                [
                'value' => (string) $note,
                'languageLocaleID' => (string) $note->attributes()->languageLocaleID,
                ]
            );
        }
    }

    private function setAdditionalDocumentReferences(stdClass $invoice): void
    {
        $invoice->additionalDocumentReferences = [];

        foreach ($this->xml->xpath('//cac:AdditionalDocumentReference') as $data) {
            $invoice->additionalDocumentReferences[] = AdditionalDocumentReference::hydrate(
                [
                'ID' => new ID((string) $data->xpath('cbc:ID')[0]),
                'documentTypeCode' => DocumentTypeCode::hydrate(
                    [
                    'value' => (string) $data->xpath('cbc:DocumentTypeCode')[0],
                    'listAgencyName' => (string) $data->xpath('cbc:DocumentTypeCode')[0]->attributes()->listAgencyName,
                    'listName' => (string) $data->xpath('cbc:DocumentTypeCode')[0]->attributes()->listName,
                    'listURI' => (string) $data->xpath('cbc:DocumentTypeCode')[0]->attributes()->listURI,
                    ]
                ),
                ]
            );
        }
    }

    private function setAccountingSupplier(stdClass $invoice): void
    {
        $accountingSupplier = new stdClass();
        $accountingSupplier->partyIdentificationID = null;
        $accountingSupplier->partyLegalEntity = null;

        if ($data = $this->xml->xpath('//cac:AccountingSupplierParty/cac:Party/cac:PartyIdentification/cbc:ID')) {
            $data = $data[0];
            $accountingSupplier->partyIdentificationID = PartyIdentificationID::hydrate(
                [
                'value' => (string) $data,
                'schemeAgencyName' => (string) $data->attributes()->schemeAgencyName,
                'schemeID' => (string) $data->attributes()->schemeID,
                'schemeName' => (string) $data->attributes()->schemeName,
                'schemeURI' => (string) $data->attributes()->schemeURI,
                ]
            );
        }

        $partyLegalEntity = new stdClass();
        $partyLegalEntity->registrationName = null;
        $partyLegalEntity->registrationAddress = null;

        if ($data = $this->xml->xpath('//cac:AccountingSupplierParty/cac:Party/cac:PartyLegalEntity/cbc:RegistrationName')) {
            $partyLegalEntity->registrationName = RegistrationName::hydrate(
                [
                'value' => (string) $data[0],
                ]
            );
        }

        if ($data = $this->xml->xpath('//cac:AccountingSupplierParty/cac:Party/cac:PartyLegalEntity/cac:RegistrationAddress')) {
            $data = $data[0];
            $registrationAddress = new stdClass();

            $registrationAddress->addressTypeCode = null;
            if ($addressTypeCode = $data->xpath('cbc:AddressTypeCode')) {
                $addressTypeCode = $addressTypeCode[0];
                $registrationAddress->addressTypeCode = AddressTypeCode::hydrate(
                    [
                    'value' => (string) $addressTypeCode,
                    'listAgencyName' => (string) $addressTypeCode->attributes()->listAgencyName,
                    'listName' => (string) $addressTypeCode->attributes()->listName,
                    ]
                );
            }

            $registrationAddress->citySubdivisionName = !empty($data->xpath('cbc:CitySubdivisionName'))
                ? CitySubdivisionName::hydrate(
                    [
                    'value' => (string) $data->xpath('cbc:CitySubdivisionName')[0],
                    ]
                )
                : null;
            $registrationAddress->cityName = !empty($data->xpath('cbc:CityName'))
                ? CityName::hydrate(
                    [
                    'value' => (string) $data->xpath('cbc:CityName')[0],
                    ]
                )
                : null;
            $registrationAddress->countrySubEntity = !empty($data->xpath('cbc:CountrySubentity'))
                ? CountrySubEntity::hydrate(
                    [
                    'value' => (string) $data->xpath('cbc:CountrySubentity')[0],
                    ]
                )
                : null;
            $registrationAddress->countrySubEntityCode = !empty($data->xpath('cbc:CountrySubentityCode'))
                ? CountrySubEntityCode::hydrate(
                    [
                    'value' => (string) $data->xpath('cbc:CountrySubentityCode')[0],
                    ]
                )
                : null;
            $registrationAddress->district = !empty($data->xpath('cbc:District'))
                ? District::hydrate(
                    [
                    'value' => (string) $data->xpath('cbc:District')[0],
                    ]
                )
                : null;
            $registrationAddress->addressLine = AddressLine::hydrate(
                [
                'value' => (string) $data->xpath('cac:AddressLine/cbc:Line')[0],
                ]
            );
            $registrationAddress->countryIdentificationCode = !empty($data->xpath('cac:Country/cbc:IdentificationCode'))
                ? CountryIdentificationCode::hydrate(
                    [
                    'value' => (string) $data->xpath('cac:Country/cbc:IdentificationCode')[0],
                    ]
                )
                : null;

            $partyLegalEntity->registrationAddress = RegistrationAddress::hydrate($registrationAddress);
        }

        $accountingSupplier->partyLegalEntity = PartyLegalEntity::hydrate($partyLegalEntity);

        $invoice->accountingSupplier = AccountingSupplier::hydrate($accountingSupplier);
    }

    private function setAccountingCustomer(stdClass $invoice): void
    {
        $accountingCustomer = new stdClass();
        $accountingCustomer->partyIdentificationID = null;
        $accountingCustomer->partyLegalEntity = null;

        if ($data = $this->xml->xpath('//cac:AccountingCustomerParty/cac:Party/cac:PartyIdentification/cbc:ID')) {
            $data = $data[0];
            $accountingCustomer->partyIdentificationID = PartyIdentificationID::hydrate(
                [
                'value' => (string) $data,
                'schemeAgencyName' => (string) $data->attributes()->schemeAgencyName,
                'schemeID' => (string) $data->attributes()->schemeID,
                'schemeName' => (string) $data->attributes()->schemeName,
                'schemeURI' => (string) $data->attributes()->schemeURI,
                ]
            );
        }

        $partyLegalEntity = new stdClass();
        $partyLegalEntity->registrationName = null;
        $partyLegalEntity->registrationAddress = null;

        if ($data = $this->xml->xpath('//cac:AccountingCustomerParty/cac:Party/cac:PartyLegalEntity/cbc:RegistrationName')) {
            $partyLegalEntity->registrationName = RegistrationName::hydrate(
                [
                'value' => (string) $data[0],
                ]
            );
        }

        if ($data = $this->xml->xpath('//cac:AccountingCustomerParty/cac:Party/cac:PartyLegalEntity/cac:RegistrationAddress')) {
            $data = $data[0];
            $registrationAddress = new stdClass();

            $registrationAddress->addressTypeCode = null;
            if ($addressTypeCode = $data->xpath('cbc:AddressTypeCode')) {
                $addressTypeCode = $addressTypeCode[0];
                $registrationAddress->addressTypeCode = AddressTypeCode::hydrate(
                    [
                    'value' => (string) $addressTypeCode,
                    'listAgencyName' => (string) $addressTypeCode->attributes()->listAgencyName,
                    'listName' => (string) $addressTypeCode->attributes()->listName,
                    ]
                );
            }

            $registrationAddress->citySubdivisionName = !empty($data->xpath('cbc:CitySubdivisionName'))
                ? CitySubdivisionName::hydrate(
                    [
                    'value' => (string) $data->xpath('cbc:CitySubdivisionName')[0],
                    ]
                )
                : null;
            $registrationAddress->cityName = !empty($data->xpath('cbc:CityName'))
                ? CityName::hydrate(
                    [
                    'value' => (string) $data->xpath('cbc:CityName')[0],
                    ]
                )
                : null;
            $registrationAddress->countrySubEntity = !empty($data->xpath('cbc:CountrySubentity'))
                ? CountrySubEntity::hydrate(
                    [
                    'value' => (string) $data->xpath('cbc:CountrySubentity')[0],
                    ]
                )
                : null;
            $registrationAddress->countrySubEntityCode = !empty($data->xpath('cbc:CountrySubentityCode'))
                ? CountrySubEntityCode::hydrate(
                    [
                    'value' => (string) $data->xpath('cbc:CountrySubentityCode')[0],
                    ]
                )
                : null;
            $registrationAddress->district = !empty($data->xpath('cbc:District'))
                ? District::hydrate(
                    [
                    'value' => (string) $data->xpath('cbc:District')[0],
                    ]
                )
                : null;
            $registrationAddress->addressLine = AddressLine::hydrate(
                [
                'value' => (string) $data->xpath('cac:AddressLine/cbc:Line')[0],
                ]
            );
            $registrationAddress->countryIdentificationCode = !empty($data->xpath('cac:Country/cbc:IdentificationCode'))
                ? CountryIdentificationCode::hydrate(
                    [
                    'value' => (string) $data->xpath('cac:Country/cbc:IdentificationCode')[0],
                    ]
                )
                : null;

            $partyLegalEntity->registrationAddress = RegistrationAddress::hydrate($registrationAddress);
        }

        $accountingCustomer->partyLegalEntity = PartyLegalEntity::hydrate($partyLegalEntity);

        $invoice->accountingCustomer = AccountingCustomer::hydrate($accountingCustomer);
    }

    private function setBuyerCustomer(stdClass $invoice): void
    {
        $buyerCustomer = new stdClass();
        $buyerCustomer->partyIdentificationID = null;
        $buyerCustomer->partyLegalEntity = null;

        if ($data = $this->xml->xpath('//cac:BuyerCustomerParty/cac:Party/cac:PartyIdentification/cbc:ID')) {
            $data = $data[0];
            $buyerCustomer->partyIdentificationID = PartyIdentificationID::hydrate(
                [
                'value' => (string) $data,
                'schemeAgencyName' => (string) $data->attributes()->schemeAgencyName,
                'schemeID' => (string) $data->attributes()->schemeID,
                'schemeName' => (string) $data->attributes()->schemeName,
                'schemeURI' => (string) $data->attributes()->schemeURI,
                ]
            );
        }

        $partyLegalEntity = new stdClass();
        $partyLegalEntity->registrationName = null;
        $partyLegalEntity->registrationAddress = null;

        if ($data = $this->xml->xpath('//cac:BuyerCustomerParty/cac:Party/cac:PartyLegalEntity/cbc:RegistrationName')) {
            $partyLegalEntity->registrationName = RegistrationName::hydrate(
                [
                'value' => (string) $data[0],
                ]
            );
        }

        if ($data = $this->xml->xpath('//cac:BuyerCustomerParty/cac:Party/cac:PartyLegalEntity/cac:RegistrationAddress')) {
            $data = $data[0];
            $registrationAddress = new stdClass();

            $registrationAddress->addressTypeCode = null;
            if ($addressTypeCode = $data->xpath('cbc:AddressTypeCode')) {
                $addressTypeCode = $addressTypeCode[0];
                $registrationAddress->addressTypeCode = AddressTypeCode::hydrate(
                    [
                    'value' => (string) $addressTypeCode,
                    'listAgencyName' => (string) $addressTypeCode->attributes()->listAgencyName,
                    'listName' => (string) $addressTypeCode->attributes()->listName,
                    ]
                );
            }

            $registrationAddress->citySubdivisionName = !empty($data->xpath('cbc:CitySubdivisionName'))
                ? CitySubdivisionName::hydrate(
                    [
                    'value' => (string) $data->xpath('cbc:CitySubdivisionName')[0],
                    ]
                )
                : null;
            $registrationAddress->cityName = !empty($data->xpath('cbc:CityName'))
                ? CityName::hydrate(
                    [
                    'value' => (string) $data->xpath('cbc:CityName')[0],
                    ]
                )
                : null;
            $registrationAddress->countrySubEntity = !empty($data->xpath('cbc:CountrySubentity'))
                ? CountrySubEntity::hydrate(
                    [
                    'value' => (string) $data->xpath('cbc:CountrySubentity')[0],
                    ]
                )
                : null;
            $registrationAddress->countrySubEntityCode = !empty($data->xpath('cbc:CountrySubentityCode'))
                ? CountrySubEntityCode::hydrate(
                    [
                    'value' => (string) $data->xpath('cbc:CountrySubentityCode')[0],
                    ]
                )
                : null;
            $registrationAddress->district = !empty($data->xpath('cbc:District'))
                ? District::hydrate(
                    [
                    'value' => (string) $data->xpath('cbc:District')[0],
                    ]
                )
                : null;
            $registrationAddress->addressLine = AddressLine::hydrate(
                [
                'value' => (string) $data->xpath('cac:AddressLine/cbc:Line')[0],
                ]
            );
            $registrationAddress->countryIdentificationCode = !empty($data->xpath('cac:Country/cbc:IdentificationCode'))
                ? CountryIdentificationCode::hydrate(
                    [
                    'value' => (string) $data->xpath('cac:Country/cbc:IdentificationCode')[0],
                    ]
                )
                : null;

            $partyLegalEntity->registrationAddress = RegistrationAddress::hydrate($registrationAddress);
        }

        $buyerCustomer->partyLegalEntity = PartyLegalEntity::hydrate($partyLegalEntity);

        $invoice->buyerCustomer = BuyerCustomer::hydrate($buyerCustomer);
    }

    private function setPaymentTerms(stdClass $invoice): void
    {
        $invoice->paymentTerms = [];

        if (!empty($this->xml->xpath('//cac:PaymentTerms'))) {
            foreach ($this->xml->xpath('//cac:PaymentTerms') as $data) {
                $invoice->paymentTerms[] = PaymentTerm::hydrate(
                    [
                    'ID' => new ID((string) $data->xpath('cbc:ID')[0]),
                    'paymentMeansID' => new ID((string) $data->xpath('cbc:PaymentMeansID')[0]),
                    'amount' => !empty($data->xpath('cbc:Amount'))
                        ? Amount::hydrate(
                            [
                            'value' => (float) $data->xpath('cbc:Amount')[0],
                            'currencyID' => (string) $data->xpath('cbc:Amount')[0]->attributes()->currencyID,
                            ]
                        )
                        : null,
                    ]
                );
            }
        }
    }

    private function setTaxTotal(stdClass $invoice): void
    {
        $data = $this->xml->xpath('//cac:TaxTotal')[0];

        $taxTotal = new stdClass();

        $taxTotal->taxAmount = Amount::hydrate(
            [
            'value' => (float) $data->xpath('cbc:TaxAmount')[0],
            'currencyID' => (string) $data->xpath('cbc:TaxAmount')[0]->attributes()->currencyID,
            ]
        );

        $taxTotal->taxSubtotals = [];

        if (!empty($data->xpath('cac:TaxSubtotal'))) {
            foreach ($data->xpath('cac:TaxSubtotal') as $subtotalData) {
                $taxTotal->taxSubtotals[] = TaxSubtotal::hydrate(
                    [
                    'taxableAmount' => Amount::hydrate(
                        [
                        'value' => (float) $subtotalData->xpath('cbc:TaxableAmount')[0],
                        'currencyID' => (string) $subtotalData->xpath('cbc:TaxableAmount')[0]->attributes()->currencyID,
                        ]
                    ),
                    'taxAmount' => Amount::hydrate(
                        [
                        'value' => (float) $subtotalData->xpath('cbc:TaxAmount')[0],
                        'currencyID' => (string) $subtotalData->xpath('cbc:TaxAmount')[0]->attributes()->currencyID,
                        ]
                    ),
                    'taxCategory' => TaxCategory::hydrate(
                        [
                        'ID' => !empty($subtotalData->xpath('cac:TaxCategory/cbc:ID')) ? TaxCategoryID::hydrate(
                            [
                            'value' => (string) $subtotalData->xpath('cac:TaxCategory/cbc:ID')[0],
                            'schemeAgencyName' => (string) $subtotalData->xpath('cac:TaxCategory/cbc:ID')[0]->attributes()->schemeAgencyName,
                            'schemeID' => (string) $subtotalData->xpath('cac:TaxCategory/cbc:ID')[0]->attributes()->schemeID,
                            'schemeName' => (string) $subtotalData->xpath('cac:TaxCategory/cbc:ID')[0]->attributes()->schemeName,
                            ]
                        ) : null,
                        'taxScheme' => TaxScheme::hydrate(
                            [
                            'ID' => TaxSchemeID::hydrate(
                                [
                                'value' => (string) $subtotalData->xpath('cac:TaxCategory/cac:TaxScheme/cbc:ID')[0],
                                'schemeAgencyName' => (string) $subtotalData->xpath('cac:TaxCategory/cac:TaxScheme/cbc:ID')[0]->attributes()->schemeAgencyName,
                                'schemeID' => (string) $subtotalData->xpath('cac:TaxCategory/cac:TaxScheme/cbc:ID')[0]->attributes()->schemeID,
                                'schemeName' => (string) $subtotalData->xpath('cac:TaxCategory/cac:TaxScheme/cbc:ID')[0]->attributes()->schemeName,
                                ]
                            ),
                            'name' => TaxSchemeName::hydrate(
                                [
                                'value' => (string) $subtotalData->xpath('cac:TaxCategory/cac:TaxScheme/cbc:Name')[0],
                                ]
                            ),
                            'taxTypeCode' => TaxSchemeTaxTypeCode::hydrate(
                                [
                                'value' => (string) $subtotalData->xpath('cac:TaxCategory/cac:TaxScheme/cbc:TaxTypeCode')[0],
                                ]
                            ),
                            ]
                        )
                        ]
                    )
                    ]
                );
            }
        }

        $invoice->taxTotal = TaxTotal::hydrate($taxTotal);
    }

    private function setLegalMonetaryTotal(stdClass $invoice): void
    {
        $data = $this->xml->xpath('//cac:LegalMonetaryTotal')[0];
        $legalMonetaryTotal = new stdClass();

        $legalMonetaryTotal->lineExtensionAmount = null;
        $legalMonetaryTotal->taxInclusiveAmount = null;
        $legalMonetaryTotal->allowanceTotalAmount = null;
        $legalMonetaryTotal->chargeTotalAmount = null;
        $legalMonetaryTotal->prepaidAmount = null;
        $legalMonetaryTotal->payableAmount = null;

        if ($lineExtensionAmount = $data->xpath('cbc:LineExtensionAmount')) {
            $lineExtensionAmount = $lineExtensionAmount[0];
            $legalMonetaryTotal->lineExtensionAmount = Amount::hydrate(
                [
                'value' => (float) $lineExtensionAmount,
                'currencyID' => (string) $lineExtensionAmount->attributes()->currencyID,
                ]
            );
        }

        if ($taxInclusiveAmount = $data->xpath('cbc:TaxInclusiveAmount')) {
            $taxInclusiveAmount = $taxInclusiveAmount[0];
            $legalMonetaryTotal->taxInclusiveAmount = Amount::hydrate(
                [
                'value' => (float) $taxInclusiveAmount,
                'currencyID' => (string) $taxInclusiveAmount->attributes()->currencyID,
                ]
            );
        }

        if ($allowanceTotalAmount = $data->xpath('cbc:AllowanceTotalAmount')) {
            $allowanceTotalAmount = $allowanceTotalAmount[0];
            $legalMonetaryTotal->allowanceTotalAmount = Amount::hydrate(
                [
                'value' => (float) $allowanceTotalAmount,
                'currencyID' => (string) $allowanceTotalAmount->attributes()->currencyID,
                ]
            );
        }

        if ($chargeTotalAmount = $data->xpath('cbc:ChargeTotalAmount')) {
            $chargeTotalAmount = $chargeTotalAmount[0];
            $legalMonetaryTotal->chargeTotalAmount = Amount::hydrate(
                [
                'value' => (float) $chargeTotalAmount,
                'currencyID' => (string) $chargeTotalAmount->attributes()->currencyID,
                ]
            );
        }

        if ($prepaidAmount = $data->xpath('cbc:PrepaidAmount')) {
            $prepaidAmount = $prepaidAmount[0];
            $legalMonetaryTotal->prepaidAmount = Amount::hydrate(
                [
                'value' => (float) $prepaidAmount,
                'currencyID' => (string) $prepaidAmount->attributes()->currencyID,
                ]
            );
        }

        if ($payableAmount = $data->xpath('cbc:PayableAmount')) {
            $payableAmount = $payableAmount[0];
            $legalMonetaryTotal->payableAmount = Amount::hydrate(
                [
                'value' => (float) $payableAmount,
                'currencyID' => (string) $payableAmount->attributes()->currencyID,
                ]
            );
        }

        $invoice->legalMonetaryTotal = LegalMonetaryTotal::hydrate($legalMonetaryTotal);
    }

    private function setInvoiceLines(stdClass $invoice): void
    {
        $invoice->invoiceLines = [];

        if ($lines = $this->xml->xpath('//cac:InvoiceLine')) {
            foreach ($lines as $line) {
                $invoice->invoiceLines[] = InvoiceLine::hydrate(
                    [
                    // Número de orden del Ítem
                    'ID' => new ID((string) $line->xpath('cbc:ID')[0]),
                    // Cantidad y Unidad de Medida por ítem
                    'invoicedQuantity' => InvoicedQuantity::hydrate(
                        [
                        'value' => (float) $line->xpath('cbc:InvoicedQuantity')[0],
                        'unitCode' => (string) $line->xpath('cbc:InvoicedQuantity')[0]->attributes()->unitCode,
                        'unitCodeListAgencyName' => (string) $line->xpath('cbc:InvoicedQuantity')[0]->attributes()->unitCodeListAgencyName,
                        'unitCodeListID' => (string) $line->xpath('cbc:InvoicedQuantity')[0]->attributes()->unitCodeListID,
                        ]
                    ),
                    // Valor de venta por ítem
                    'lineExtensionAmount' => Amount::hydrate(
                        [
                        'value' => (float) $line->xpath('cbc:LineExtensionAmount')[0],
                        'currencyID' => (string) $line->xpath('cbc:LineExtensionAmount')[0]->attributes()->currencyID,
                        ]
                    ),
                    'freeOfChargeIndicator' => !empty($line->xpath('cbc:FreeOfChargeIndicator'))
                        ? FreeOfChargeIndicator::hydrate(
                            [
                            'value' => json_decode((string) $line->xpath('cbc:FreeOfChargeIndicator')[0]),
                            ]
                        )
                        : null,
                    // Precio de venta unitario por ítem y código (01)
                    // Valor referencial unitario por ítem en operaciones no onerosas (02)
                    'pricingReferences' =>
                        collect($line->xpath('cac:PricingReference/cac:AlternativeConditionPrice'))
                            ->map(
                                function (SimpleXMLElement $price) {
                                    return PricingReference::hydrate(
                                        [
                                        'alternativeConditionPrice' => AlternativeConditionPrice::hydrate(
                                            [
                                            'priceAmount' => Amount::hydrate(
                                                [
                                                'value' => (float) $price->xpath('cbc:PriceAmount')[0],
                                                'currencyID' => (string) $price->xpath('cbc:PriceAmount')[0]->attributes()->currencyID,
                                                ]
                                            ),
                                            'priceTypeCode' => PriceTypeCode::hydrate(
                                                [
                                                'value' => (string) $price->xpath('cbc:PriceTypeCode')[0],
                                                'listAgencyName' => (string) $price->xpath('cbc:PriceTypeCode')[0]->attributes()->listAgencyName,
                                                'listName' => (string) $price->xpath('cbc:PriceTypeCode')[0]->attributes()->listName,
                                                'listURI' => (string) $price->xpath('cbc:PriceTypeCode')[0]->attributes()->listURI,
                                                ]
                                            ),
                                            ]
                                        ),
                                        ]
                                    );
                                }
                            )
                            ->toArray(),
                    // Descuentos (chargeindicator false), Cargos por ítem (charge indicator true)
                    'allowanceCharges' =>
                        collect($line->xpath('cac:AllowanceCharge'))
                            ->map(
                                function (SimpleXMLElement $charge) {
                                    return AllowanceCharge::hydrate(
                                        [
                                        'chargeIndicator' => ChargeIndicator::hydrate(
                                            [
                                            'value' => json_decode((string) $charge->xpath('cbc:ChargeIndicator')[0]),
                                            ]
                                        ),
                                        'amount' => Amount::hydrate(
                                            [
                                            'value' => (float) $charge->xpath('cbc:Amount')[0],
                                            'currencyID' => (string) $charge->xpath('cbc:Amount')[0]->attributes()->currencyID,
                                            ]
                                        ),
                                        // TODO: AllowanceChargeReasonCode, MultiplierFactorNumeric, BaseAmount
                                        ]
                                    );
                                }
                            )
                            ->toArray(),
                    'taxTotal' => TaxTotal::hydrate(
                        [
                        'taxAmount' => Amount::hydrate(
                            [
                            'value' => (float) $line->xpath('cac:TaxTotal/cbc:TaxAmount')[0],
                            'currencyID' => (string) $line->xpath('cac:TaxTotal/cbc:TaxAmount')[0]->attributes()->currencyID,
                            ]
                        ),
                        'taxSubtotals' => collect($line->xpath('cac:TaxTotal/cac:TaxSubtotal'))
                            ->map(
                                function (SimpleXMLElement $subtotal) {
                                    return TaxSubtotal::hydrate(
                                        [
                                        'taxableAmount' => Amount::hydrate(
                                            [
                                            'value' => (float) $subtotal->xpath('cbc:TaxableAmount')[0],
                                            'currencyID' => (string) $subtotal->xpath('cbc:TaxableAmount')[0]->attributes()->currencyID,
                                            ]
                                        ),
                                        'taxAmount' => Amount::hydrate(
                                            [
                                            'value' => (float) $subtotal->xpath('cbc:TaxAmount')[0],
                                            'currencyID' => (string) $subtotal->xpath('cbc:TaxAmount')[0]->attributes()->currencyID,
                                            ]
                                        ),
                                        'taxCategory' => TaxCategory::hydrate(
                                            [
                                            'ID' => !empty($subtotal->xpath('cac:TaxCategory/cbc:ID')) ? TaxCategoryID::hydrate(
                                                [
                                                'value' => (string) $subtotal->xpath('cac:TaxCategory/cbc:ID')[0],
                                                'schemeAgencyName' => (string) $subtotal->xpath('cac:TaxCategory/cbc:ID')[0]->attributes()->schemeAgencyName,
                                                'schemeID' => (string) $subtotal->xpath('cac:TaxCategory/cbc:ID')[0]->attributes()->schemeID,
                                                'schemeName' => (string) $subtotal->xpath('cac:TaxCategory/cbc:ID')[0]->attributes()->schemeName,
                                                ]
                                            ) : null,
                                            'percent' => Percent::hydrate(
                                                [
                                                'value' => (float) $subtotal->xpath('cac:TaxCategory/cbc:Percent')[0],
                                                ]
                                            ),
                                            'tierRange' => !empty($subtotal->xpath('cac:TaxCategory/cbc:TierRange'))
                                            ? TierRange::hydrate(
                                                [
                                                'value' => (string) $subtotal->xpath('cac:TaxCategory/cbc:TierRange')[0],
                                                ]
                                            )
                                            : null,
                                            'taxExemptionReasonCode' => !empty($subtotal->xpath('cac:TaxCategory/cbc:TaxExemptionReasonCode'))
                                            ? TaxExemptionReasonCode::hydrate(
                                                [
                                                'value' => (string) $subtotal->xpath('cac:TaxCategory/cbc:TaxExemptionReasonCode')[0],
                                                'listAgencyName' => (string) $subtotal->xpath('cac:TaxCategory/cbc:TaxExemptionReasonCode')[0]->attributes()->listAgencyName,
                                                'listName' => (string) $subtotal->xpath('cac:TaxCategory/cbc:TaxExemptionReasonCode')[0]->attributes()->listName,
                                                'listURI' => (string) $subtotal->xpath('cac:TaxCategory/cbc:TaxExemptionReasonCode')[0]->attributes()->listURI,
                                                ]
                                            )
                                            : null,
                                            'taxScheme' => TaxScheme::hydrate(
                                                [
                                                'ID' => TaxSchemeID::hydrate(
                                                    [
                                                    'value' => (string) $subtotal->xpath('cac:TaxCategory/cac:TaxScheme/cbc:ID')[0],
                                                    'schemeAgencyName' => (string) $subtotal->xpath('cac:TaxCategory/cac:TaxScheme/cbc:ID')[0]->attributes()->schemeAgencyName,
                                                    'schemeID' => (string) $subtotal->xpath('cac:TaxCategory/cac:TaxScheme/cbc:ID')[0]->attributes()->schemeID,
                                                    'schemeName' => (string) $subtotal->xpath('cac:TaxCategory/cac:TaxScheme/cbc:ID')[0]->attributes()->schemeName,
                                                    ]
                                                ),
                                                'name' => TaxSchemeName::hydrate(
                                                    [
                                                    'value' => (string) $subtotal->xpath('cac:TaxCategory/cac:TaxScheme/cbc:Name')[0],
                                                    ]
                                                ),
                                                'taxTypeCode' => TaxSchemeTaxTypeCode::hydrate(
                                                    [
                                                    'value' => (string) $subtotal->xpath('cac:TaxCategory/cac:TaxScheme/cbc:TaxTypeCode')[0],
                                                    ]
                                                )
                                                ]
                                            ),
                                            ]
                                        ),
                                        ]
                                    );
                                }
                            )
                            ->toArray(),
                        'item' => Item::hydrate(
                            [
                            'description' => Description::hydrate(
                                [
                                'value' => (string) $line->xpath('cac:Item/cbc:Description')[0],
                                ]
                            ),
                            'sellersItemIdentification' => SellersItemIdentification::hydrate(
                                [
                                'ID' => new ID((string) $line->xpath('cac:Item/cac:SellersItemIdentification/cbc:ID')[0]),
                                ]
                            )
                            ]
                        ),
                        'price' => Price::hydrate(
                            [
                            'priceAmount' => Amount::hydrate(
                                [
                                'value' => (float) $line->xpath('cac:Price/cbc:PriceAmount')[0],
                                'currencyID' => (string) $line->xpath('cac:Price/cbc:PriceAmount')[0]->attributes()->currencyID,
                                ]
                            ),
                            ]
                        ),
                        ]
                    )
                    ]
                );
            }
        }
    }
}
