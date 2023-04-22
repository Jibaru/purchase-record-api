<?php

namespace Core\PurchaseRecords\Domain\Entities;

use Carbon\Carbon;
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
use Core\Vouchers\Domain\Entities\ValueObjects\VoucherID;
use stdClass;

class PurchaseRecord
{
    private PurchaseRecordID $ID;
    private Period $period;
    private UniqueOperationCode $uniqueOperationCode;
    private CorrelativeAccountingEntryNumber $correlativeAccountingEntryNumber;
    private DayMonthYearDate $issueDate;
    private ?DayMonthYearDate $dueDate;
    private VoucherType $voucherType;
    private ?VoucherSeries $voucherSeries;
    private ?DuaOrDsiIssueYear $duaOrDsiIssueYear;
    private VoucherNumber $voucherNumber;
    private ?DailyOperationsTotalAmount $dailyOperationsTotalAmount;
    private ?SupplierDocumentType $supplierDocumentType;
    private ?SupplierDocumentNumber $supplierDocumentNumber;
    private ?SupplierDocumentDenomination $supplierDocumentDenomination;
    private ?TaxBase $firstTaxBase;
    private ?IgvAmount $firstIgvAmount;
    private ?TaxBase $secondTaxBase;
    private ?IgvAmount $secondIgvAmount;
    private ?TaxBase $thirdTaxBase;
    private ?IgvAmount $thirdIgvAmount;
    /*private ?float $acquisitions_untaxed_value;
    private ?float $isc_amount;
    private ?float $icbper_amount;
    private ?float $other_concepts;
    private float $acquisitions_total_amount;
    private ?string $currency_code;
    private ?float $exchange_rate;
    private ?string $modify_issue_date;
    private ?string $modify_voucher_type;
    private ?string $modify_voucher_series;
    private ?string $customs_dependency_code;
    private ?string $modify_voucher_number;
    private ?string $issue_date_certificate_deposit_detraction;
    private ?string $number_certificate_deposit_detraction;
    private ?string $voucher_mark_subject_to_retention;
    private ?string $classification_goods_services_acquired;
    private ?string $contract_identification;
    private ?string $error_type_one;
    private ?string $error_type_two;
    private ?string $error_type_three;
    private ?string $error_type_four;
    private ?string $vouchers_canceled_with_payment_methods;
    private ?string $state;*/
    private VoucherID $voucherID;
    private Carbon $createdAt;
    private Carbon $updatedAt;

    public function __construct(
        PurchaseRecordID $ID,
        VoucherID $voucherID,
        Period $period,
        UniqueOperationCode $uniqueOperationCode,
        CorrelativeAccountingEntryNumber $correlativeAccountingEntryNumber,
        DayMonthYearDate $issueDate,
        ?DayMonthYearDate $dueDate,
        VoucherType $voucherType,
        ?VoucherSeries $voucherSeries,
        ?DuaOrDsiIssueYear $duaOrDsiIssueYear,
        VoucherNumber $voucherNumber,
        ?DailyOperationsTotalAmount $dailyOperationsTotalAmount,
        ?SupplierDocumentType $supplierDocumentType,
        ?SupplierDocumentNumber $supplierDocumentNumber,
        ?SupplierDocumentDenomination $supplierDocumentDenomination,
        ?TaxBase $firstTaxBase,
        ?IgvAmount $firstIgvAmount,
        ?TaxBase $secondTaxBase,
        ?IgvAmount $secondIgvAmount,
        ?TaxBase $thirdTaxBase,
        ?IgvAmount $thirdIgvAmount,
        Carbon $createdAt,
        Carbon $updatedAt
    ) {
        $this->ID = $ID;
        $this->voucherID = $voucherID;
        $this->period = $period;
        $this->uniqueOperationCode = $uniqueOperationCode;
        $this->correlativeAccountingEntryNumber = $correlativeAccountingEntryNumber;
        $this->issueDate = $issueDate;
        $this->dueDate = $dueDate;
        $this->voucherType = $voucherType;
        $this->voucherSeries = $voucherSeries;
        $this->duaOrDsiIssueYear = $duaOrDsiIssueYear;
        $this->voucherNumber = $voucherNumber;
        $this->dailyOperationsTotalAmount = $dailyOperationsTotalAmount;
        $this->supplierDocumentType = $supplierDocumentType;
        $this->supplierDocumentNumber = $supplierDocumentNumber;
        $this->supplierDocumentDenomination = $supplierDocumentDenomination;
        $this->firstTaxBase = $firstTaxBase;
        $this->firstIgvAmount = $firstIgvAmount;
        $this->secondTaxBase = $secondTaxBase;
        $this->secondIgvAmount = $secondIgvAmount;
        $this->thirdTaxBase = $thirdTaxBase;
        $this->thirdIgvAmount = $thirdIgvAmount;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public static function hydrate(array|stdClass $fields): self
    {
        return new self(
            $fields['id'],
            $fields['voucher_id'],
            $fields['period'],
            $fields['unique_operation_code'],
            $fields['correlative_accounting_entry_number'],
            $fields['issue_date'],
            $fields['due_date'],
            $fields['voucher_type'],
            $fields['voucher_series'],
            $fields['dua_or_dsi_issue_year'],
            $fields['voucher_number'],
            $fields['daily_operations_total_amount'],
            $fields['supplier_document_type'],
            $fields['supplier_document_number'],
            $fields['supplier_document_denomination'],
            $fields['first_tax_base'],
            $fields['first_igv_amount'],
            $fields['second_tax_base'],
            $fields['second_igv_amount'],
            $fields['third_tax_base'],
            $fields['third_igv_amount'],
            $fields['created_at'],
            $fields['updated_at'],
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->ID->value,
            'voucher_id' => $this->voucherID->value,
            'period' => $this->period->toPurchaseRecordFormat(),
            'unique_operation_code' => $this->uniqueOperationCode->value,
            'correlative_accounting_entry_number' => $this->correlativeAccountingEntryNumber->value,
            'issue_date' => $this->issueDate->toPurchaseRecordFormat(),
            'due_date' => $this->dueDate?->toPurchaseRecordFormat(),
            'voucher_type' => $this->voucherType->value,
            'voucher_series' => $this->voucherSeries?->value,
            'dua_or_dsi_issue_year' => $this->duaOrDsiIssueYear?->value,
            'voucher_number' => $this->voucherNumber->number,
            'daily_operations_total_amount' => $this->dailyOperationsTotalAmount?->value,
            'supplier_document_type' => $this->supplierDocumentType?->value,
            'supplier_document_number' => $this->supplierDocumentNumber?->value,
            'supplier_document_denomination' => $this->supplierDocumentDenomination?->value,
            'first_tax_base' => $this->firstTaxBase?->value,
            'first_igv_amount' => $this->firstIgvAmount?->value,
            'second_tax_base' => $this->secondTaxBase?->value,
            'second_igv_amount' => $this->secondIgvAmount?->value,
            'third_tax_base' => $this->thirdTaxBase?->value,
            'third_igv_amount' => $this->thirdIgvAmount?->value,
            'created_at' => $this->createdAt->toDateTimeString(),
            'updated_at' => $this->updatedAt->toDateTimeString(),
        ];
    }
}
