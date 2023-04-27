<?php

namespace Core\Vouchers\Application\Parser\Values;

use Core\Vouchers\Application\Parser\Values\Contracts\Arrayable;
use Core\Vouchers\Application\Parser\Values\Helpers\Filler;
use Core\Vouchers\Application\Parser\Values\Traits\IsArrayable;
use stdClass;

class PaymentMean implements Arrayable
{
    use IsArrayable;

    public readonly ID $ID;
    public readonly PaymentMeansCode $paymentMeansCode;
    public readonly PayeeFinancialAccount $payeeFinancialAccount;

    /**
     * @param ID $ID
     * @param PaymentMeansCode $paymentMeansCode
     * @param PayeeFinancialAccount $payeeFinancialAccount
     */
    public function __construct(
        ID $ID,
        PaymentMeansCode $paymentMeansCode,
        PayeeFinancialAccount $payeeFinancialAccount
    ) {
        $this->ID = $ID;
        $this->paymentMeansCode = $paymentMeansCode;
        $this->payeeFinancialAccount = $payeeFinancialAccount;
    }

    public static function hydrate(array|stdClass $from): self
    {
        $obj = (object) $from;
        Filler::withNull($obj, self::class);

        return new self(
            $obj->ID instanceof ID
                ? $obj->ID
                : ID::hydrate($obj->ID),
            $obj->paymentMeansCode instanceof PaymentMeansCode
                ? $obj->paymentMeansCode
                : PaymentMeansCode::hydrate($obj->paymentMeansCode),
            $obj->payeeFinancialAccount instanceof PayeeFinancialAccount
                ? $obj->payeeFinancialAccount
                : PayeeFinancialAccount::hydrate($obj->payeeFinancialAccount),
        );
    }
}
