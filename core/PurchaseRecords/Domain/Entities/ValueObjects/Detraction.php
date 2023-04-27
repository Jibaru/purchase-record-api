<?php

namespace Core\PurchaseRecords\Domain\Entities\ValueObjects;

class Detraction
{
    public readonly float $percentage;

    public function __construct(float $percentage)
    {
        $this->percentage = $percentage;
    }

    public function make(float $percentage): self
    {
        return new self($percentage);
    }
}
