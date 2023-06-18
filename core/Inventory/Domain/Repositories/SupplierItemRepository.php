<?php

namespace Core\Inventory\Domain\Repositories;

use Core\Inventory\Domain\Dtos\SimpleItemDto;
use Core\Inventory\Domain\Entities\SupplierItem;
use Core\Inventory\Domain\Entities\ValueObjects\SupplierReference;
use Core\PurchaseRecords\Domain\Repositories\Specifications\Specification;

interface SupplierItemRepository
{
    public function store(SupplierItem $supplierItem): void;

    /**
     * @param int $page
     * @param int|null $perPage
     * @param Specification|null $specification
     * @return SimpleItemDto[]
     */
    public function getSimpleItemRows(int $page, ?int $perPage = null, ?Specification $specification = null): array;
    public function getTotalPages(int $perPage, ?Specification $specification = null): int;

    /**
     * @param SupplierReference $reference
     * @return SimpleItemDto[]
     */
    public function getSimpleSupplierItemRowsBySupplierReference(SupplierReference $reference): array;
}
