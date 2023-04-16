<?php

namespace Core\Vouchers\Application\Parser\Values;

use stdClass;
use Core\Vouchers\Application\Parser\Values\Contracts\Arrayable;
use Core\Vouchers\Application\Parser\Values\Helpers\Filler;
use Core\Vouchers\Application\Parser\Values\Traits\IsArrayable;

class InvoiceLine implements Arrayable
{
    use IsArrayable;

    public readonly ID $ID;
    public readonly InvoicedQuantity $invoicedQuantity;
    public readonly Amount $lineExtensionAmount;
    public readonly TaxTotal $taxTotal;
    public readonly ?FreeOfChargeIndicator $freeOfChargeIndicator;

    /**
     * @var PricingReference[]
     */
    public readonly array $pricingReferences;

    /**
     * @var AllowanceCharge[]
     */
    public readonly array $allowanceCharges;

    public function __construct(
        ID $ID,
        InvoicedQuantity $invoicedQuantity,
        Amount $lineExtensionAmount,
        TaxTotal $taxTotal,
        ?FreeOfChargeIndicator $freeOfChargeIndicator,
        array $pricingReferences,
        array $allowanceCharges
    ) {
        $this->ID = $ID;
        $this->invoicedQuantity = $invoicedQuantity;
        $this->lineExtensionAmount = $lineExtensionAmount;
        $this->taxTotal = $taxTotal;
        $this->freeOfChargeIndicator = $freeOfChargeIndicator;
        $this->pricingReferences = $pricingReferences;
        $this->allowanceCharges = $allowanceCharges;
    }

    public static function hydrate(array|stdClass $from): self
    {
        $obj = (object) $from;
        Filler::withNull($obj, self::class);

        return new self(
            $obj->ID instanceof ID
                ? $obj->ID
                : ID::hydrate($obj->ID),
            $obj->invoicedQuantity instanceof InvoicedQuantity
                ? $obj->invoicedQuantity
                : InvoicedQuantity::hydrate($obj->invoicedQuantity),
            $obj->lineExtensionAmount instanceof Amount
                ? $obj->lineExtensionAmount
                : Amount::hydrate($obj->lineExtensionAmount),
            $obj->taxTotal instanceof TaxTotal
                ? $obj->taxTotal
                : TaxTotal::hydrate($obj->taxTotal),
            $obj->freeOfChargeIndicator instanceof FreeOfChargeIndicator || is_null($obj->freeOfChargeIndicator)
                ? $obj->freeOfChargeIndicator
                : FreeOfChargeIndicator::hydrate($obj->freeOfChargeIndicator),
            $obj->pricingReferences,
            $obj->allowanceCharges
        );
    }
}
