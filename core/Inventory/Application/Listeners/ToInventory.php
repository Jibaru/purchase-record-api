<?php

namespace Core\Inventory\Application\Listeners;

use Core\Inventory\Domain\Entities\Factories\SupplierFactory;
use Core\Inventory\Domain\Repositories\SupplierItemRepository;
use Core\Inventory\Domain\Repositories\SupplierRepository;
use Core\PurchaseRecords\Application\Events\PurchaseRecordCreated;
use Core\Vouchers\Domain\Repositories\VoucherRepository;
use Exception;

class ToInventory
{
    private SupplierFactory $supplierFactory;
    private VoucherRepository $voucherRepository;
    private SupplierRepository $supplierRepository;
    private SupplierItemRepository $supplierItemRepository;

    public function __construct(
        SupplierFactory $supplierFactory,
        VoucherRepository $voucherRepository,
        SupplierRepository $supplierRepository,
        SupplierItemRepository $supplierItemRepository
    ) {
        $this->supplierFactory = $supplierFactory;
        $this->voucherRepository = $voucherRepository;
        $this->supplierRepository = $supplierRepository;
        $this->supplierItemRepository = $supplierItemRepository;
    }


    /**
     * @param PurchaseRecordCreated $event
     * @return void
     * @throws Exception
     */
    public function handle(PurchaseRecordCreated $event): void
    {
        $purchaseRecord = $event->purchaseRecord;
        $voucher = $this->voucherRepository->getByVoucherID($purchaseRecord->voucherID());

        $supplier = $this->supplierFactory->buildFromPurchaseRecord($purchaseRecord);
        $supplierItems = $this->supplierFactory->buildItemsFromVoucher($voucher, $supplier->id());

        $this->supplierRepository->store($supplier);

        foreach ($supplierItems as $supplierItem) {
            $this->supplierItemRepository->store($supplierItem);
        }
    }
}
