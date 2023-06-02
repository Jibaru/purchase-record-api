<?php

namespace Core\PurchaseRecords\Application\Exportables;

use Core\PurchaseRecords\Domain\Dtos\PurchaseRecordDTO;
use Core\PurchaseRecords\Domain\Repositories\PurchaseRecordRepository;
use Core\PurchaseRecords\Domain\Repositories\Specifications\Specification;
use Maatwebsite\Excel\Excel;

class CompletePurchaseRecordsExportable implements PurchaseRecordsExportable
{
    private PurchaseRecordRepository $purchaseRecordRepository;
    private ?Specification $specification = null;

    public function __construct(PurchaseRecordRepository $purchaseRecordRepository)
    {
        $this->purchaseRecordRepository = $purchaseRecordRepository;
    }

    public function setSpecification(Specification $specification): void
    {
        $this->specification = $specification;
    }

    public function filename(): string
    {
        return 'purchase-records.xls';
    }

    public function writerType(): string
    {
        return Excel::XLSX;
    }

    public function headers(): array
    {
        return [
            'Content-Type' => 'text/csv',
        ];
    }

    public function headings(): array
    {
        return [
            'PERIODO',
            'CODIGO ÚNICO DE OPERACIÓN',
            'FECHA EMISIÓN',
            'FECHA VENCIMIENTO',
            'TIPO COMPROBANTE',
            'SERIE COMPROBANTE',
            'NRO COMPROBANTE',
            'DUA/DSI AÑO',
            'TOTAL OPERACIONES DIARIAS',
            'TIPO DOCUMENTO PROVEEDOR',
            'NRO DOCUMENTO PROVEEDOR',
            'DENOMINACIÓN PROVEEDOR',
            'IMP. 1',
            'IGV 1',
            'IMP. 2',
            'IGV 2',
            'IMP. 3',
            'IGV 3',
            'MONTO PAGADO',
            'TIENE DETRACCIÓN',
            'PORCENTAJE DETRACCIÓN',
        ];
    }

    public function data(): array
    {
        return $this->purchaseRecordRepository->getPurchaseRecordsRows(1, null, $this->specification);
    }

    /**
     * @param PurchaseRecordDTO $purchaseRecord
     * @return array
     */
    public function map($purchaseRecord): array
    {
        return [
            $purchaseRecord->period,
            $purchaseRecord->uniqueOperationCode,
            $purchaseRecord->issueDate,
            $purchaseRecord->dueDate ?? '-',
            $purchaseRecord->voucherType,
            $purchaseRecord->voucherSeries,
            $purchaseRecord->voucherNumber,
            $purchaseRecord->duaOrDsiIssueYear ?? '-',
            $purchaseRecord->dailyOperationsTotalAmount ?? '0',
            $purchaseRecord->supplierDocumentType,
            $purchaseRecord->supplierDocumentNumber,
            $purchaseRecord->supplierDocumentDenomination,
            $purchaseRecord->firstTaxBase ?? '0',
            $purchaseRecord->firstIgvAmount ?? '0',
            $purchaseRecord->secondTaxBase ?? '0',
            $purchaseRecord->secondIgvAmount ?? '0',
            $purchaseRecord->thirdTaxBase ?? '0',
            $purchaseRecord->thirdIgvAmount ?? '0',
            $purchaseRecord->payableAmount ?? '0',
            $purchaseRecord->hasDetraction ? 'SI' : 'NO',
            $purchaseRecord->detractionPercentage ?? '0',
        ];
    }
}
