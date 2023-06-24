<?php

namespace Core\Inventory\Application\UseCases;

use Core\Inventory\Domain\Dtos\SimpleItemDto;
use Core\Inventory\Domain\Entities\ValueObjects\SupplierReference;
use Core\Inventory\Domain\Repositories\SupplierItemRepository;

class GetItemsBySupplierReferenceUseCase
{
    private SupplierItemRepository $supplierItemRepository;

    public function __construct(SupplierItemRepository $supplierItemRepository)
    {
        $this->supplierItemRepository = $supplierItemRepository;
    }

    /**
     * @param SupplierReference $reference
     * @return SimpleItemDto[]
     */
    public function __invoke(SupplierReference $reference): array
    {
        return $this->supplierItemRepository->getSimpleSupplierItemRowsBySupplierReference($reference);
    }
}
