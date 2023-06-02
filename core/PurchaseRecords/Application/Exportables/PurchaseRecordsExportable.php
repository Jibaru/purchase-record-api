<?php

namespace Core\PurchaseRecords\Application\Exportables;

use Core\PurchaseRecords\Domain\Repositories\Specifications\Specification;

interface PurchaseRecordsExportable
{
    public function filename(): string;
    public function writerType(): string;
    public function headers(): array;
    public function headings(): array;
    public function data(): array;
    public function map($purchaseRecord): array;
    public function setSpecification(Specification $specification): void;
}
