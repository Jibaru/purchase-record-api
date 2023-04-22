<?php

namespace Core\Vouchers\Domain\Entities;

use Carbon\Carbon;
use Core\Vouchers\Application\Parser\Values\Invoice;
use Core\Vouchers\Domain\Entities\ValueObjects\VoucherContent;
use Core\Vouchers\Domain\Entities\ValueObjects\VoucherID;
use Core\Vouchers\Domain\Entities\ValueObjects\VoucherType;
use Core\Vouchers\Domain\Entities\ValueObjects\VoucherXMLContent;

class Voucher
{
    public readonly VoucherID $id;
    private VoucherType $type;
    private VoucherContent $content;

    private VoucherXMLContent $xmlContent;
    public readonly Carbon $createdAt;
    public readonly Carbon $updatedAt;

    public function __construct(
        VoucherID $id,
        VoucherType $type,
        VoucherContent $content,
        VoucherXMLContent $xmlContent,
        Carbon $createdAt,
        Carbon $updatedAt
    ) {
        $this->id = $id;
        $this->type = $type;
        $this->content = $content;
        $this->xmlContent = $xmlContent;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id->value,
            'type' => $this->type->value,
            'content' => json_encode($this->content->value),
            'xml_content' => $this->xmlContent->value,
            'created_at' => $this->createdAt->toDateTimeString(),
            'updated_at' => $this->updatedAt->toDateTimeString(),
        ];
    }

    public function parseContent(): Invoice
    {
        return Invoice::hydrate($this->content->value);
    }
}
