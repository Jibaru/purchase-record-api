<?php

namespace Core\PurchaseRecords\Domain\Dtos;

use stdClass;

class PurchaseRecordDTO
{
    public readonly string $id;
    public readonly string $period;
    public readonly string $uniqueOperationCode;
    public readonly string $correlativeAccountingEntryNumber;
    public readonly string $issueDate;
    public readonly ?string $dueDate;
    public readonly string $voucherType;
    public readonly ?string $voucherSeries;
    public readonly ?string $duaOrDsiIssueYear;
    public readonly string $voucherNumber;
    public readonly ?string $dailyOperationsTotalAmount;
    public readonly ?string $supplierDocumentType;
    public readonly ?string $supplierDocumentNumber;
    public readonly ?string $supplierDocumentDenomination;
    public readonly ?float $firstTaxBase;
    public readonly ?float $firstIgvAmount;
    public readonly ?float $secondTaxBase;
    public readonly ?float $secondIgvAmount;
    public readonly ?float $thirdTaxBase;
    public readonly ?float $thirdIgvAmount;
    public readonly float $payableAmount;
    public readonly bool $hasDetraction;
    public readonly ?float $detractionPercentage;

    /**
     * @param string $id
     * @param string $period
     * @param string $uniqueOperationCode
     * @param string $correlativeAccountingEntryNumber
     * @param string $issueDate
     * @param string|null $dueDate
     * @param string $voucherType
     * @param string|null $voucherSeries
     * @param string|null $duaOrDsiIssueYear
     * @param string $voucherNumber
     * @param string|null $dailyOperationsTotalAmount
     * @param string|null $supplierDocumentType
     * @param string|null $supplierDocumentNumber
     * @param string|null $supplierDocumentDenomination
     * @param float|null $firstTaxBase
     * @param float|null $firstIgvAmount
     * @param float|null $secondTaxBase
     * @param float|null $secondIgvAmount
     * @param float|null $thirdTaxBase
     * @param float|null $thirdIgvAmount
     * @param float $payableAmount
     * @param bool $hasDetraction
     * @param float|null $detractionPercentage
     */
    public function __construct(
        string $id,
        string $period,
        string $uniqueOperationCode,
        string $correlativeAccountingEntryNumber,
        string $issueDate,
        ?string $dueDate,
        string $voucherType,
        ?string $voucherSeries,
        ?string $duaOrDsiIssueYear,
        string $voucherNumber,
        ?string $dailyOperationsTotalAmount,
        ?string $supplierDocumentType,
        ?string $supplierDocumentNumber,
        ?string $supplierDocumentDenomination,
        ?float $firstTaxBase,
        ?float $firstIgvAmount,
        ?float $secondTaxBase,
        ?float $secondIgvAmount,
        ?float $thirdTaxBase,
        ?float $thirdIgvAmount,
        float $payableAmount,
        bool $hasDetraction,
        ?float $detractionPercentage,
    ) {
        $this->id = $id;
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
        $this->payableAmount = $payableAmount;
        $this->hasDetraction = $hasDetraction;
        $this->detractionPercentage = $detractionPercentage;
    }

    public static function hydrate(array|stdClass $fields): self
    {
        $fields = (object) $fields;

        return new self(
            $fields->id,
            $fields->period,
            $fields->unique_operation_code,
            $fields->correlative_accounting_entry_number,
            $fields->issue_date,
            $fields->due_date,
            $fields->voucher_type,
            $fields->voucher_series,
            $fields->dua_or_dsi_issue_year,
            $fields->voucher_number,
            $fields->daily_operations_total_amount,
            $fields->supplier_document_type,
            $fields->supplier_document_number,
            $fields->supplier_document_denomination,
            $fields->first_tax_base,
            $fields->first_igv_amount,
            $fields->second_tax_base,
            $fields->second_igv_amount,
            $fields->third_tax_base,
            $fields->third_igv_amount,
            $fields->payable_amount,
            $fields->has_detraction,
            $fields->detraction_percentage,
        );
    }
}
