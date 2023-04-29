<?php

namespace Core\Vouchers\Infrastructure\Resources;

use Core\Vouchers\Application\Parser\Values\InvoiceLine;
use Core\Vouchers\Application\Parser\Values\Note;
use Core\Vouchers\Application\Parser\Values\TaxSubtotal;
use Core\Vouchers\Domain\Entities\Voucher;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DetailedVoucherResource extends JsonResource
{
    /**
     * The resource instance.
     *
     * @var Voucher
     */
    public $resource;

    public function toArray(Request $request)
    {
        $content = $this->resource->parseContent();

        return [
            'id' => $this->resource->id->value,
            'series_number' => $content->ID->value,
            'issue_date' => $content->issueDate->value,
            'due_date' => $content->dueDate?->value,
            'type' => $content->invoiceTypeCode->value,
            'notes' => collect($content->notes)->map(fn (Note $note) => $note->value)->toArray(),
            'currency' => $content->documentCurrencyCode->value,
            'supplier' => [
                'name' => $content->accountingSupplier?->partyLegalEntity?->registrationName?->value,
                'document' => [
                    'type' => $content->accountingSupplier?->partyIdentificationID?->schemeID,
                    'value' => $content->accountingSupplier?->partyIdentificationID?->value
                ],
                'address' => $content->accountingSupplier?->partyLegalEntity?->registrationAddress?->addressLine?->value,
            ],
            'customer' => [
                'name' => $content->accountingCustomer?->partyLegalEntity?->registrationName?->value,
                'document' => [
                    'type' => $content->accountingCustomer?->partyIdentificationID?->schemeID,
                    'value' => $content->accountingCustomer?->partyIdentificationID?->value
                ],
                'address' => $content->accountingCustomer?->partyLegalEntity?->registrationAddress?->addressLine?->value,
            ],
            'lines' => collect($content->invoiceLines)->map(function (InvoiceLine $line) {
                return [
                    'quantity' => $line->invoicedQuantity->value,
                    'unit' => $line->invoicedQuantity->unitCode,
                    'unit_value' => $line?->price?->priceAmount?->value,
                    'total' => $line->lineExtensionAmount->value,
                    'description' => $line?->item?->description?->value,
                ];
            })->toArray(),
            'summary' => [
                'subtotal' => $content->legalMonetaryTotal?->lineExtensionAmount?->value ?? 0,
                'discount' => $content->legalMonetaryTotal?->allowanceTotalAmount?->value ?? 0,
                'total' => $content->legalMonetaryTotal?->payableAmount?->value ?? 0,
                'other_charges' => $content->legalMonetaryTotal?->chargeTotalAmount?->value ?? 0,
                'prepaid' => $content->legalMonetaryTotal?->prepaidAmount?->value ?? 0,
                'igv' => collect($content->taxTotal->taxSubtotals)->filter(function (TaxSubtotal $taxSubtotal) {
                    return $taxSubtotal->taxCategory->taxScheme->ID->value == '1000';
                })->reduce(fn (float $acc, TaxSubtotal $taxSubtotal) => $acc + $taxSubtotal->taxAmount->value, 0.0),
                'isc' => collect($content->taxTotal->taxSubtotals)->filter(function (TaxSubtotal $taxSubtotal) {
                    return $taxSubtotal->taxCategory->taxScheme->ID->value == '2000';
                })->reduce(fn (float $acc, TaxSubtotal $taxSubtotal) => $acc + $taxSubtotal->taxAmount->value, 0.0),
                'icbper' => collect($content->taxTotal->taxSubtotals)->filter(function (TaxSubtotal $taxSubtotal) {
                    return $taxSubtotal->taxCategory->taxScheme->ID->value == '7152';
                })->reduce(fn (float $acc, TaxSubtotal $taxSubtotal) => $acc + $taxSubtotal->taxAmount->value, 0.0),
                'other_tributes' => collect($content->taxTotal->taxSubtotals)->filter(function (TaxSubtotal $taxSubtotal) {
                    return $taxSubtotal->taxCategory->taxScheme->ID->value == '9999';
                })->reduce(fn (float $acc, TaxSubtotal $taxSubtotal) => $acc + $taxSubtotal->taxAmount->value, 0.0),
            ]
        ];
    }
}
