<?php

namespace Core\Vouchers\Application\Parser\Values;

use stdClass;
use Core\Vouchers\Application\Parser\Values\Contracts\Arrayable;
use Core\Vouchers\Application\Parser\Values\Helpers\Filler;
use Core\Vouchers\Application\Parser\Values\Traits\IsArrayable;

class PaymentTerm implements Arrayable
{
    use IsArrayable;

    public readonly ID $ID;
    public readonly ID $paymentMeansID;
    public readonly ?Amount $amount;
    public readonly ?PaymentPercent $paymentPercent;

    public function __construct(
        ID $ID,
        ID $paymentMeansID,
        ?Amount $amount,
        ?PaymentPercent $paymentPercent
    ) {
        $this->ID = $ID;
        $this->paymentMeansID = $paymentMeansID;
        $this->amount = $amount;
        $this->paymentPercent = $paymentPercent;
    }

    public static function hydrate(array|stdClass $from): self
    {
        $obj = (object) $from;
        Filler::withNull($obj, self::class);

        return new self(
            $obj->ID instanceof ID
                ? $obj->ID
                : ID::hydrate($obj->ID),
            $obj->paymentMeansID instanceof ID
                ? $obj->paymentMeansID
                : ID::hydrate($obj->paymentMeansID),
            $obj->amount instanceof Amount || is_null($obj->amount)
                ? $obj->amount
                : Amount::hydrate($obj->amount),
            $obj->paymentPercent instanceof PaymentPercent || is_null($obj->paymentPercent)
                ? $obj->paymentPercent
                : PaymentPercent::hydrate($obj->paymentPercent),
        );
    }
}
