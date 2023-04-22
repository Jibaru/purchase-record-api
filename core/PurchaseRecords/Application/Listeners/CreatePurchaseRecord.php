<?php

namespace Core\PurchaseRecords\Application\Listeners;

use Carbon\Carbon;
use Core\PurchaseRecords\Domain\Entities\PurchaseRecord;
use Core\PurchaseRecords\Domain\Entities\ValueObjects\CorrelativeAccountingEntryNumber;
use Core\PurchaseRecords\Domain\Entities\ValueObjects\DailyOperationsTotalAmount;
use Core\PurchaseRecords\Domain\Entities\ValueObjects\DayMonthYearDate;
use Core\PurchaseRecords\Domain\Entities\ValueObjects\DuaOrDsiIssueYear;
use Core\PurchaseRecords\Domain\Entities\ValueObjects\IgvAmount;
use Core\PurchaseRecords\Domain\Entities\ValueObjects\Period;
use Core\PurchaseRecords\Domain\Entities\ValueObjects\PurchaseRecordID;
use Core\PurchaseRecords\Domain\Entities\ValueObjects\SupplierDocumentDenomination;
use Core\PurchaseRecords\Domain\Entities\ValueObjects\SupplierDocumentNumber;
use Core\PurchaseRecords\Domain\Entities\ValueObjects\SupplierDocumentType;
use Core\PurchaseRecords\Domain\Entities\ValueObjects\TaxBase;
use Core\PurchaseRecords\Domain\Entities\ValueObjects\UniqueOperationCode;
use Core\PurchaseRecords\Domain\Entities\ValueObjects\VoucherNumber;
use Core\PurchaseRecords\Domain\Entities\ValueObjects\VoucherSeries;
use Core\PurchaseRecords\Domain\Entities\ValueObjects\VoucherType;
use Core\PurchaseRecords\Domain\Repositories\PurchaseRecordRepository;
use Core\Vouchers\Application\Events\VoucherCreated;
use Core\Vouchers\Application\Parser\Values\Invoice;
use Exception;

class CreatePurchaseRecord
{
    /**
     * @param VoucherCreated $event
     * @return void
     * @throws Exception
     */
    public function handle(VoucherCreated $event): void
    {
        $voucher = $event->voucher;

        $invoice = $voucher->parseContent();

        $fields = [
            'id' => PurchaseRecordID::empty(),
            'voucher_id' => $voucher->id,
            'period' => null,
            'unique_operation_code' => null,
            'correlative_accounting_entry_number' => null,
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
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];

        $this->register01($invoice, $fields);
        $this->register02($invoice, $fields);
        $this->register03($invoice, $fields);
        $this->register04($invoice, $fields);
        $this->register06($invoice, $fields);
        $this->register05($invoice, $fields);
        $this->register07($invoice, $fields);
        $this->register08($invoice, $fields);
        $this->register09($invoice, $fields);
        $this->register10($invoice, $fields);
        $this->register11and12and13($invoice, $fields);
        $this->register14To19($invoice, $fields);

        $repository = app(PurchaseRecordRepository::class);
        $repository->store(PurchaseRecord::hydrate($fields));
    }

    public function register01(Invoice $invoice, array &$fields): void
    {
        $fields['period'] = Period::now();
    }

    public function register02(Invoice $invoice, array &$fields): void
    {
        $fields['unique_operation_code'] = UniqueOperationCode::make();
        /*if ($fields['42'] == '0' || $fields['42'] == '1' || $fields['42'] == '6' || $fields['42'] == '7') {
            $fields['unique_operation_code'] = UniqueOperationCode::make();
        }

        if ($fields['42'] == '9') {
            $fields['unique_operation_code'] = UniqueOperationCode::make();
        }*/
    }

    public function register03(Invoice $invoice, array &$fields): void
    {
        $fields['correlative_accounting_entry_number'] = CorrelativeAccountingEntryNumber::make();
    }

    /**
     * @param Invoice $invoice
     * @param array $fields
     * @return void
     * @throws Exception
     */
    public function register04(Invoice $invoice, array &$fields): void
    {
        $fields['issue_date'] = DayMonthYearDate::fromYearMonthDay($invoice->issueDate->value);

        /** @var Period $period */
        $period = $fields['period'];
        $periodTwelveMonthsAgo = $period->addMonths(12);

        if ($fields['issue_date']->betweenPeriods($period, $periodTwelveMonthsAgo)) {
            $fields['42'] = '6';
        } else {
            $fields['42'] = '7';
        }
    }

    /**
     * @param Invoice $invoice
     * @param array $fields
     * @return void
     * @throws Exception
     */
    public function register06(Invoice $invoice, array &$fields): void
    {
        $fields['voucher_type'] = VoucherType::make($invoice->invoiceTypeCode->value);
    }

    /**
     * @param Invoice $invoice
     * @param array $fields
     * @return void
     * @throws Exception
     */
    public function register05(Invoice $invoice, array &$fields): void
    {
        /** @var VoucherType $voucherType */
        $voucherType = $fields['voucher_type'];

        if ($voucherType->inValues('14')) {
            $fields['due_date'] = DayMonthYearDate::fromYearMonthDay($invoice->dueDate->value);
        }
    }

    /**
     * @param Invoice $invoice
     * @param array $fields
     * @return void
     * @throws Exception
     */
    public function register07(Invoice $invoice, array &$fields): void
    {
        $fields['voucher_series'] = VoucherSeries::fromSeriesNumber($invoice->ID->value);
    }

    /**
     * @param Invoice $invoice
     * @param array $fields
     * @return void
     * @throws Exception
     */
    public function register08(Invoice $invoice, array &$fields): void
    {
        /** @var VoucherType $voucherType */
        $voucherType = $fields['voucher_type'];

        if ($voucherType->inValues('50', '52')) {
            $fields['dua_or_dsi_issue_year'] = DuaOrDsiIssueYear::make((int) date('Y'));
        }
    }

    /**
     * @param Invoice $invoice
     * @param array $fields
     * @return void
     * @throws Exception
     */
    public function register09(Invoice $invoice, array &$fields): void
    {
        $fields['voucher_number'] = VoucherNumber::fromSeriesNumber($invoice->ID->value);
    }

    public function register10(Invoice $invoice, array &$fields): void
    {
        /** @var VoucherType $voucherType */
        $voucherType = $fields['voucher_type'];

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
            ) && $fields['voucher_number']->value >= 0
        ) {
            $fields['daily_operations_total_amount'] = DailyOperationsTotalAmount::make(0); // TODO: buscar
        }
    }

    /**
     * @param Invoice $invoice
     * @param array $fields
     * @return void
     * @throws Exception
     */
    public function register11and12and13(Invoice $invoice, array &$fields): void
    {
        /** @var VoucherType $voucherType */
        $voucherType = $fields['voucher_type'];

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
                $fields['28'] == [
                    '03',
                    '12',
                    '13',
                    '14',
                    '36',
                ]
            )
        ) {
            if (isset($invoice->accountingSupplier->partyIdentificationID->schemeID)) {
                $fields['supplier_document_type'] = SupplierDocumentType::make($invoice->accountingSupplier->partyIdentificationID->schemeID);
                $fields['supplier_document_number'] = SupplierDocumentNumber::make($invoice->accountingSupplier->partyIdentificationID->value);
                $fields['supplier_document_denomination'] = SupplierDocumentDenomination::make($invoice->accountingSupplier->partyLegalEntity->registrationName->value);
                return;
            }
        }

        $fields['supplier_document_type'] = SupplierDocumentType::make($invoice->accountingSupplier->partyIdentificationID->schemeID);
        $fields['supplier_document_number'] = SupplierDocumentNumber::make($invoice->accountingSupplier->partyIdentificationID->value);
        $fields['supplier_document_denomination'] = SupplierDocumentDenomination::make($invoice->accountingSupplier->partyLegalEntity->registrationName->value);
    }

    public function register14To19(Invoice $invoice, array &$fields): void
    {
        foreach ($invoice->taxTotal->taxSubtotals as $taxSubtotal) {
            if ($taxSubtotal->taxCategory->taxScheme->name->value == 'IGV') {
                $fields['first_tax_base'] = TaxBase::make($taxSubtotal->taxableAmount->value);
                $fields['first_igv_amount'] = IgvAmount::make($taxSubtotal->taxAmount->value);
            }

            if ($taxSubtotal->taxCategory->taxScheme->name->value == 'EXONERADO') {
                $fields['second_tax_base'] = TaxBase::make($taxSubtotal->taxableAmount->value);
                $fields['second_igv_amount'] = IgvAmount::make($taxSubtotal->taxAmount->value);
            }

            if ($taxSubtotal->taxCategory->taxScheme->name->value == 'INAFECTO') {
                $fields['third_tax_base'] = TaxBase::make($taxSubtotal->taxableAmount->value);
                $fields['third_igv_amount'] = IgvAmount::make($taxSubtotal->taxAmount->value);
            }
        }
    }
}
