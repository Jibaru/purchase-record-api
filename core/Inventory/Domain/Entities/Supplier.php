<?php

namespace Core\Inventory\Domain\Entities;

use Carbon\Carbon;
use Core\Inventory\Domain\Entities\ValueObjects\SupplierID;
use Core\Inventory\Domain\Entities\ValueObjects\SupplierReference;
use Core\PurchaseRecords\Domain\Entities\ValueObjects\Period;
use Core\PurchaseRecords\Domain\Entities\ValueObjects\PurchaseRecordID;
use Core\PurchaseRecords\Domain\Entities\ValueObjects\SupplierDocumentDenomination;
use Core\PurchaseRecords\Domain\Entities\ValueObjects\SupplierDocumentNumber;
use Core\PurchaseRecords\Domain\Entities\ValueObjects\SupplierDocumentType;

class Supplier
{
    private SupplierID $id;
    private SupplierReference $reference;
    private SupplierDocumentType $documentType;
    private SupplierDocumentDenomination $documentDenomination;
    private SupplierDocumentNumber $documentNumber;
    private Period $period;
    private PurchaseRecordID $purchaseRecordID;
    private Carbon $createdAt;
    private Carbon $updatedAt;

    public function __construct(
        SupplierID $id,
        SupplierReference $reference,
        SupplierDocumentType $documentType,
        SupplierDocumentDenomination $documentDenomination,
        SupplierDocumentNumber $documentNumber,
        Period $period,
        PurchaseRecordID $purchaseRecordID,
        Carbon $createdAt,
        Carbon $updatedAt
    ) {
        $this->id = $id;
        $this->reference = $reference;
        $this->documentType = $documentType;
        $this->documentDenomination = $documentDenomination;
        $this->documentNumber = $documentNumber;
        $this->period = $period;
        $this->purchaseRecordID = $purchaseRecordID;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public function id(): SupplierID
    {
        return $this->id;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id->value,
            'reference' => $this->reference->value,
            'document_type' => $this->documentType->value,
            'document_number' => $this->documentNumber->value,
            'document_denomination' => $this->documentDenomination->value,
            'period' => $this->period->toPurchaseRecordFormat(),
            'purchase_record_id' => $this->purchaseRecordID->value,
            'created_at' => $this->createdAt->toDateTimeString(),
            'updated_at' => $this->updatedAt->toDateTimeString(),
        ];
    }
}
