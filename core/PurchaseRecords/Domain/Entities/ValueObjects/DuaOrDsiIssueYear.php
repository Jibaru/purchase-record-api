<?php

namespace Core\PurchaseRecords\Domain\Entities\ValueObjects;

use Exception;

class DuaOrDsiIssueYear
{
    public const ORDER = 8;

    public readonly int $value;

    /**
     * @param int $value
     * @throws Exception
     */
    public function __construct(int $value)
    {
        $this->setValue($value);
    }

    /**
     * @param int $value
     * @return void
     * @throws Exception
     */
    private function setValue(int $value): void
    {
        if (strlen((string) $value) != 4) {
            throw new Exception('value must have 4 characters');
        }

        $this->value = $value;
    }

    /**
     * @param int $value
     * @return self
     * @throws Exception
     */
    public static function make(int $value): self
    {
        return new self($value);
    }
}
