<?php

namespace Core\Vouchers\Application\Parser\Values;

use stdClass;
use Core\Vouchers\Application\Parser\Values\Contracts\Arrayable;
use Core\Vouchers\Application\Parser\Values\Helpers\Filler;
use Core\Vouchers\Application\Parser\Values\Traits\IsArrayable;

class LegalMonetaryTotal implements Arrayable
{
    use IsArrayable;

    public readonly ?Amount $lineExtensionAmount;
    public readonly ?Amount $taxInclusiveAmount;
    public readonly ?Amount $allowanceTotalAmount;
    public readonly ?Amount $chargeTotalAmount;
    public readonly ?Amount $prepaidAmount;
    public readonly ?Amount $payableAmount;

    public function __construct(
        ?Amount $lineExtensionAmount,
        ?Amount $taxInclusiveAmount,
        ?Amount $allowanceTotalAmount,
        ?Amount $chargeTotalAmount,
        ?Amount $prepaidAmount,
        ?Amount $payableAmount
    ) {
        $this->lineExtensionAmount = $lineExtensionAmount;
        $this->taxInclusiveAmount = $taxInclusiveAmount;
        $this->allowanceTotalAmount = $allowanceTotalAmount;
        $this->chargeTotalAmount = $chargeTotalAmount;
        $this->prepaidAmount = $prepaidAmount;
        $this->payableAmount = $payableAmount;
    }

    public static function hydrate(array|stdClass $from): self
    {
        $obj = (object) $from;
        Filler::withNull($obj, self::class);

        return new self(
            $obj->lineExtensionAmount instanceof Amount || is_null($obj->lineExtensionAmount)
                ? $obj->lineExtensionAmount
                : Amount::hydrate($obj->lineExtensionAmount),
            $obj->taxInclusiveAmount instanceof Amount || is_null($obj->taxInclusiveAmount)
                ? $obj->taxInclusiveAmount
                : Amount::hydrate($obj->taxInclusiveAmount),
            $obj->allowanceTotalAmount instanceof Amount || is_null($obj->allowanceTotalAmount)
                ? $obj->allowanceTotalAmount
                : Amount::hydrate($obj->allowanceTotalAmount),
            $obj->chargeTotalAmount instanceof Amount || is_null($obj->chargeTotalAmount)
                ? $obj->chargeTotalAmount
                : Amount::hydrate($obj->chargeTotalAmount),
            $obj->prepaidAmount instanceof Amount || is_null($obj->prepaidAmount)
                ? $obj->prepaidAmount
                : Amount::hydrate($obj->prepaidAmount),
            $obj->payableAmount instanceof Amount || is_null($obj->payableAmount)
                ? $obj->payableAmount
                : Amount::hydrate($obj->payableAmount),
        );
    }
}
