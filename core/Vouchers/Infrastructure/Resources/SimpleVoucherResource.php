<?php

namespace Core\Vouchers\Infrastructure\Resources;

use Carbon\Carbon;
use Core\Vouchers\Domain\Entities\Voucher;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SimpleVoucherResource extends JsonResource
{
    /**
     * @var Voucher
     */
    public $resource;

    public function toArray(Request $request): array
    {
        $content = $this->resource->parseContent();

        return [
            'id' => $this->resource->id->value,
            'created_at' => $this->resource->createdAt,
            'issue_date' => Carbon::parse($content->issueDate->value . ' ' . $content->issueTime->value),
            'customer' => $content->accountingCustomer?->partyLegalEntity?->registrationName->value,
            'supplier' => $content->accountingSupplier?->partyLegalEntity?->registrationName->value,
            'series_number' => $content->ID->value,
            'total' => [
                'currency' => $content->legalMonetaryTotal?->payableAmount->currencyID,
                'amount' => $content->legalMonetaryTotal?->payableAmount->value,
            ],
        ];
    }
}
