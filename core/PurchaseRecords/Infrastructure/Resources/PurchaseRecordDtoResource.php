<?php

namespace Core\PurchaseRecords\Infrastructure\Resources;

use Core\PurchaseRecords\Domain\Dtos\PurchaseRecordDTO;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseRecordDtoResource extends JsonResource
{
    /**
     * The resource instance.
     *
     * @var PurchaseRecordDTO
     */
    public $resource;

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'period' => $this->resource->period,
            'unique_operation_code' => $this->resource->uniqueOperationCode,
            'correlative_accounting_entry_number' => $this->resource->correlativeAccountingEntryNumber,
            'issue_date' => $this->resource->issueDate,
            'due_date' => $this->resource->dueDate,
            'voucher_type' => $this->resource->voucherType,
            'voucher_series' => $this->resource->voucherSeries,
            'dua_or_dsi_issue_year' => $this->resource->duaOrDsiIssueYear,
            'voucher_number' => $this->resource->voucherNumber,
            'daily_operations_total_amount' => $this->resource->dailyOperationsTotalAmount,
            'supplier_document_type' => $this->resource->supplierDocumentType,
            'supplier_document_number' => $this->resource->supplierDocumentNumber,
            'supplier_document_denomination' => $this->resource->supplierDocumentDenomination,
            'first_tax_base' => $this->resource->firstTaxBase,
            'first_igv_amount' => $this->resource->firstIgvAmount,
            'second_tax_base' => $this->resource->secondTaxBase,
            'second_igv_amount' => $this->resource->secondIgvAmount,
            'third_tax_base' => $this->resource->thirdTaxBase,
            'third_igv_amount' => $this->resource->thirdIgvAmount,
        ];
    }
}
