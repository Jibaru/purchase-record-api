<?php

namespace Core\Inventory\Application\UseCases;

use Core\Inventory\Domain\Dtos\SimpleSupplierDto;
use Core\Inventory\Domain\Entities\ValueObjects\SupplierItemReference;
use Core\Inventory\Domain\Repositories\SupplierRepository;

class GetSuppliersByItemReferenceUseCase
{
    private SupplierRepository $supplierRepository;

    public function __construct(SupplierRepository $supplierRepository)
    {
        $this->supplierRepository = $supplierRepository;
    }

    /**
     * @param SupplierItemReference $reference
     * @return SimpleSupplierDto[]
     */
    public function __invoke(SupplierItemReference $reference): array
    {
        return $this->supplierRepository->getSimpleSupplierRowsByItemReference($reference);
    }
}
