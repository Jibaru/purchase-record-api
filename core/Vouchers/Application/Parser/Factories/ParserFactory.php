<?php

namespace Core\Vouchers\Application\Parser\Factories;

use Core\Vouchers\Application\Parser\InvoiceParserService;
use Core\Vouchers\Application\Parser\Parser;

class ParserFactory
{
    public function makeInvoiceParser(string $contents): Parser
    {
        return new InvoiceParserService($contents);
    }
}
