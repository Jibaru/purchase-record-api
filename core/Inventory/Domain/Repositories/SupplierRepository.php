<?php

namespace Core\Inventory\Domain\Repositories;

use Core\Inventory\Domain\Dtos\SimpleSupplierDto;
use Core\Inventory\Domain\Entities\Supplier;
use Core\Inventory\Domain\Entities\ValueObjects\SupplierItemReference;
use Core\PurchaseRecords\Domain\Repositories\Specifications\Specification;

interface SupplierRepository
{
    public function store(Supplier $supplier): void;
    /**
     * @param int $page
     * @param int|null $perPage
     * @param Specification|null $specification
     * @return SimpleSupplierDto[]
     */
    public function getSimpleSupplierRows(int $page, ?int $perPage = null, ?Specification $specification = null): array;
    public function getTotalPages(int $perPage, ?Specification $specification = null): int;

    /**
     * @param SupplierItemReference $reference
     * @return SimpleSupplierDto[]
     */
    public function getSimpleSupplierRowsByItemReference(SupplierItemReference $reference): array;
}
