<?php

namespace Core\Vouchers\Application\Parser\Values;

use stdClass;
use Core\Vouchers\Application\Parser\Values\Contracts\Arrayable;
use Core\Vouchers\Application\Parser\Values\Helpers\Filler;
use Core\Vouchers\Application\Parser\Values\Traits\IsArrayable;

class TaxTotal implements Arrayable
{
    use IsArrayable;

    public readonly Amount $taxAmount;
    public readonly ?Item $item;
    public readonly ?Price $price;

    /**
     * @var TaxSubtotal[]
     */
    public readonly array $taxSubtotals;

    public function __construct(
        Amount $taxAmount,
        ?Item $item,
        ?Price $price,
        array $taxSubtotals
    ) {
        $this->taxAmount = $taxAmount;
        $this->item = $item;
        $this->price = $price;
        $this->taxSubtotals = $taxSubtotals;
    }

    public static function hydrate(array|stdClass $from): self
    {
        $obj = (object) $from;
        Filler::withNull($obj, self::class);

        return new self(
            $obj->taxAmount instanceof Amount
                ? $obj->taxAmount
                : Amount::hydrate($obj->taxAmount),
            $obj->item instanceof Item || is_null($obj->item)
                ? $obj->item
                : Item::hydrate($obj->item),
            $obj->price instanceof Price || is_null($obj->price)
                ? $obj->price
                : Price::hydrate($obj->price),
            is_null($obj->taxSubtotals)
                ? []
                : collect($obj->taxSubtotals)->map(
                    fn ($taxSubtotal) => $taxSubtotal instanceof TaxSubtotal
                        ? $taxSubtotal
                        : TaxSubtotal::hydrate($taxSubtotal)
                )->toArray(),
        );
    }
}
