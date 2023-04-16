<?php

namespace Core\Vouchers\Application\Parser;

use Core\Vouchers\Application\Parser\Values\Invoice;

interface Parser
{
    public function parse(): Invoice;
}
