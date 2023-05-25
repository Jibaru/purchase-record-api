<?php

namespace Core\PurchaseRecords\Domain\Entities\Builders;

use Carbon\Carbon;
use Core\PurchaseRecords\Domain\Entities\PurchaseRecord;
use Core\PurchaseRecords\Domain\Entities\ValueObjects\CorrelativeAccountingEntryNumber;
use Core\PurchaseRecords\Domain\Entities\ValueObjects\DailyOperationsTotalAmount;
use Core\PurchaseRecords\Domain\Entities\ValueObjects\DayMonthYearDate;
use Core\PurchaseRecords\Domain\Entities\ValueObjects\Detraction;
use Core\PurchaseRecords\Domain\Entities\ValueObjects\DuaOrDsiIssueYear;
use Core\PurchaseRecords\Domain\Entities\ValueObjects\IgvAmount;
use Core\PurchaseRecords\Domain\Entities\ValueObjects\Period;
use Core\PurchaseRecords\Domain\Entities\ValueObjects\PurchaseRecordID;
use Core\PurchaseRecords\Domain\Entities\ValueObjects\SummaryAmount;
use Core\PurchaseRecords\Domain\Entities\ValueObjects\SupplierDocumentDenomination;
use Core\PurchaseRecords\Domain\Entities\ValueObjects\SupplierDocumentNumber;
use Core\PurchaseRecords\Domain\Entities\ValueObjects\SupplierDocumentType;
use Core\PurchaseRecords\Domain\Entities\ValueObjects\TaxBase;
use Core\PurchaseRecords\Domain\Entities\ValueObjects\UniqueOperationCode;
use Core\PurchaseRecords\Domain\Entities\ValueObjects\VoucherNumber;
use Core\PurchaseRecords\Domain\Entities\ValueObjects\VoucherSeries;
use Core\PurchaseRecords\Domain\Entities\ValueObjects\VoucherType;
use Core\Vouchers\Application\Parser\Values\Invoice;
use Core\Vouchers\Domain\Entities\ValueObjects\VoucherID;
use Exception;

class SunatPurchaseRecordBuilder implements PurchaseRecordBuilder
{
    private array $fields;

    public function __construct(VoucherID $voucherID)
    {
        $this->fields = [
            'id' => PurchaseRecordID::empty(),
            'voucher_id' => $voucherID,
            'period' => null,
            'unique_operation_code' => UniqueOperationCode::make(),
            'correlative_accounting_entry_number' => CorrelativeAccountingEntryNumber::make(),
            'issue_date' => null,
            'due_date' => null,
            'voucher_type' => null,
            'voucher_series' => null,
            'dua_or_dsi_issue_year' => null,
            'voucher_number' => null,
            'daily_operations_total_amount' => null,
            'supplier_document_type' => null,
            'supplier_document_number' => null,
            'supplier_document_denomination' => null,
            'first_tax_base' => null,
            'first_igv_amount' => null,
            'second_tax_base' => null,
            'second_igv_amount' => null,
            'third_tax_base' => null,
            'third_igv_amount' => null,
            'acquisitions_untaxed_value' => null,
            'isc_amount' => null,
            'icbper_amount' => null,
            'other_concepts' => null,
            'acquisitions_total_amount' => null,
            'currency_code' => null,
            'exchange_rate' => null,
            'modify_issue_date' => null,
            'modify_voucher_type' => null,
            'modify_voucher_series' => null,
            'customs_dependency_code' => null,
            'modify_voucher_number' => null,
            'issue_date_certificate_deposit_detraction' => null,
            'number_certificate_deposit_detraction' => null,
            'voucher_mark_subject_to_retention' => null,
            'classification_goods_services_acquired' => null,
            'contract_identification' => null,
            'error_type_one' => null,
            'error_type_two' => null,
            'error_type_three' => null,
            'error_type_four' => null,
            'vouchers_canceled_with_payment_methods' => null,
            'state' => null,
            'payable_amount' => false,
            'has_detraction' => false,
            'detraction_percentage' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }

    /**
     * Field 01
     *
     * @return void
     */
    public function setPeriodToNow(): void
    {
        $this->fields['period'] = Period::now();
    }

    /**
     * Field 04
     *
     * @param Invoice $invoice
     * @return void
     * @throws Exception
     */
    public function setIssueDateFromInvoice(Invoice $invoice): void
    {
        $this->fields['issue_date'] = DayMonthYearDate::fromYearMonthDay($invoice->issueDate->value);

        /** @var Period $period */
        $period = $this->fields['period'];
        $periodTwelveMonthsAgo = $period->addMonths(12);

        if ($this->fields['issue_date']->betweenPeriods($period, $periodTwelveMonthsAgo)) {
            $this->fields['42'] = '6';
        } else {
            $this->fields['42'] = '7';
        }
    }

    /**
     * Field 06
     *
     * @param Invoice $invoice
     * @return void
     * @throws Exception
     */
    public function setVoucherTypeFromInvoice(Invoice $invoice): void
    {
        $fields['voucher_type'] = VoucherType::make($invoice->invoiceTypeCode->value);
    }

    /**
     * Field 05
     *
     * @param Invoice $invoice
     * @return void
     * @throws Exception
     */
    public function setDueDateFromInvoice(Invoice $invoice): void
    {
        /** @var VoucherType $voucherType */
        $voucherType = $this->fields['voucher_type'];

        if ($voucherType->inValues('14')) {
            $this->fields['due_date'] = DayMonthYearDate::fromYearMonthDay($invoice->dueDate->value);
        }
    }

    /**
     * Field 07
     *
     * @param Invoice $invoice
     * @return void
     * @throws Exception
     */
    public function setVoucherSeriesFromInvoice(Invoice $invoice): void
    {
        $this->fields['voucher_series'] = VoucherSeries::fromSeriesNumber($invoice->ID->value);
    }

    /**
     * Field 08
     *
     * @param Invoice $invoice
     * @return void
     * @throws Exception
     */
    public function setDuaOrDsiIssueYearFromInvoice(Invoice $invoice): void
    {
        /** @var VoucherType $voucherType */
        $voucherType = $this->fields['voucher_type'];

        if ($voucherType->inValues('50', '52')) {
            $this->fields['dua_or_dsi_issue_year'] = DuaOrDsiIssueYear::make((int) date('Y'));
        }
    }

    /**
     * Field 09
     *
     * @param Invoice $invoice
     * @return void
     * @throws Exception
     */
    public function setVoucherNumberFromInvoice(Invoice $invoice): void
    {
        $this->fields['voucher_number'] = VoucherNumber::fromSeriesNumber($invoice->ID->value);
    }

    /**
     * Field 10
     *
     * @param Invoice $invoice
     * @return void
     */
    public function setDailyOperationsTotalAmountFromInvoice(Invoice $invoice): void
    {
        /** @var VoucherType $voucherType */
        $voucherType = $this->fields['voucher_type'];

        if (
            (
            $voucherType->inValues(
                '00',
                '03',
                '05',
                '06',
                '07',
                '08',
                '11',
                '12',
                '13',
                '14',
                '15',
                '16',
                '18',
                '19',
                '23',
                '26',
                '28',
                '30',
                '34',
                '35',
                '36',
                '37',
                '55',
                '56',
                '87',
                '88',
            )
            ) && $this->fields['voucher_number']->value >= 0
        ) {
            $this->fields['daily_operations_total_amount'] = DailyOperationsTotalAmount::make(0); // TODO: buscar
        }
    }

    /**
     * Fields 11, 12, 13
     *
     * @param Invoice $invoice
     * @return void
     * @throws Exception
     */
    public function setSupplierInformationFromInvoice(Invoice $invoice): void
    {
        /** @var VoucherType $voucherType */
        $voucherType = $this->fields['voucher_type'];

        if (
            $voucherType->inValues(
                '00',
                '03',
                '05',
                '06',
                '07',
                '08',
                '11',
                '12',
                '13',
                '14',
                '15',
                '16',
                '18',
                '19',
                '22',
                '23',
                '26',
                '28',
                '30',
                '34',
                '35',
                '36',
                '37',
                '55',
                '56',
                '87',
                '88',
                '91',
                '97',
                '98'
            ) ||
            (
                $voucherType->inValues(
                    '07',
                    '08',
                    '87',
                    '88',
                    '97',
                    '98',
                ) &&
                $this->fields['28'] == [
                    '03',
                    '12',
                    '13',
                    '14',
                    '36',
                ]
            )
        ) {
            if (isset($invoice->accountingSupplier->partyIdentificationID->schemeID)) {
                $this->fields['supplier_document_type'] = SupplierDocumentType::make(
                    $invoice->accountingSupplier->partyIdentificationID->schemeID
                );
                $this->fields['supplier_document_number'] = SupplierDocumentNumber::make(
                    $invoice->accountingSupplier->partyIdentificationID->value
                );
                $this->fields['supplier_document_denomination'] = SupplierDocumentDenomination::make(
                    $invoice->accountingSupplier->partyLegalEntity->registrationName->value
                );
                return;
            }
        }

        $this->fields['supplier_document_type'] = SupplierDocumentType::make(
            $invoice->accountingSupplier->partyIdentificationID->schemeID
        );
        $this->fields['supplier_document_number'] = SupplierDocumentNumber::make(
            $invoice->accountingSupplier->partyIdentificationID->value
        );
        $this->fields['supplier_document_denomination'] = SupplierDocumentDenomination::make(
            $invoice->accountingSupplier->partyLegalEntity->registrationName->value
        );
    }

    /**
     * Fields 14, 15, 16, 17, 18, 19
     *
     * @param Invoice $invoice
     * @return void
     */
    public function setTaxBasesAndIgvAmountsFromInvoice(Invoice $invoice): void
    {
        foreach ($invoice->taxTotal->taxSubtotals as $taxSubtotal) {
            if ($taxSubtotal->taxCategory->taxScheme?->name?->value == 'IGV') {
                $this->fields['first_tax_base'] = TaxBase::make($taxSubtotal->taxableAmount->value);
                $this->fields['first_igv_amount'] = IgvAmount::make($taxSubtotal->taxAmount->value);
            }

            if ($taxSubtotal->taxCategory->taxScheme?->name?->value == 'EXONERADO') {
                $this->fields['second_tax_base'] = TaxBase::make($taxSubtotal->taxableAmount->value);
                $this->fields['second_igv_amount'] = IgvAmount::make($taxSubtotal->taxAmount->value);
            }

            if ($taxSubtotal->taxCategory->taxScheme?->name?->value == 'INAFECTO') {
                $this->fields['third_tax_base'] = TaxBase::make($taxSubtotal->taxableAmount->value);
                $this->fields['third_igv_amount'] = IgvAmount::make($taxSubtotal->taxAmount->value);
            }
        }
    }

    /**
     * Field 43
     *
     * @param Invoice $invoice
     * @return void
     */
    public function setPayableAmountFromInvoice(Invoice $invoice): void
    {
        $this->fields['payable_amount'] = SummaryAmount::make($invoice->legalMonetaryTotal->payableAmount->value);
    }

    /**
     * Fields 44, 45
     *
     * @param Invoice $invoice
     * @return void
     */
    public function setDetractionInformationFromInvoice(Invoice $invoice): void
    {
        $MIN_VALUE_TO_HAS_DETRACTION = 700;
        $DETRACTION_NAME = 'detraccion';

        $total = $invoice->legalMonetaryTotal->payableAmount->value;

        $hasDetractionInNotes = false;

        if (isset($invoice->notes)) {
            foreach ($invoice->notes as $note) {
                if (
                    str_contains(strtolower($note->value), $DETRACTION_NAME) ||
                    str_contains(strtolower($note->value), $DETRACTION_NAME)
                ) {
                    $hasDetractionInNotes = true;
                }
            }

            if ($total > $MIN_VALUE_TO_HAS_DETRACTION && $hasDetractionInNotes) {
                $fields['has_detraction'] = true;
                foreach ($invoice->paymentTerms as $paymentTerm) {
                    if (strtolower($paymentTerm->ID->value) == $DETRACTION_NAME) {
                        if (isset($paymentTerm->paymentPercent)) {
                            $this->fields['detraction_percentage'] = new Detraction(
                                $paymentTerm->paymentPercent->value
                            );
                        }
                    }
                }
            }
        }
    }

    public function build(): PurchaseRecord
    {
        return PurchaseRecord::hydrate($this->fields);
    }
}
