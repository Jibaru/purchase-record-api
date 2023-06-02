<?php

namespace Core\PurchaseRecords\Infrastructure\Exportables;

use Core\PurchaseRecords\Application\Exportables\PurchaseRecordsExportable as DomainPurchaseRecordsExportable;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class PurchaseRecordsExportable implements
    FromCollection,
    Responsable,
    ShouldAutoSize,
    WithColumnFormatting,
    WithHeadings,
    WithMapping,
    WithEvents
{
    use Exportable;

    private string $fileName;
    private string $writerType;
    private array $headers;

    private DomainPurchaseRecordsExportable $domainPurchaseRecordsExportable;

    public function __construct(DomainPurchaseRecordsExportable $purchaseRecordsExportable)
    {
        $this->domainPurchaseRecordsExportable = $purchaseRecordsExportable;
        $this->fileName = $purchaseRecordsExportable->filename();
        $this->writerType = $purchaseRecordsExportable->writerType();
        $this->headers = $purchaseRecordsExportable->headers();
    }

    public function collection(): Collection
    {
        return collect($this->domainPurchaseRecordsExportable->data());
    }

    public function headings(): array
    {
        return $this->domainPurchaseRecordsExportable->headings();
    }

    public function map($row): array
    {
        return $this->domainPurchaseRecordsExportable->map($row);
    }

    public function columnFormats(): array
    {
        return [
            'I' => NumberFormat::FORMAT_NUMBER_00,
            'M' => NumberFormat::FORMAT_NUMBER_00,
            'N' => NumberFormat::FORMAT_NUMBER_00,
            'O' => NumberFormat::FORMAT_NUMBER_00,
            'P' => NumberFormat::FORMAT_NUMBER_00,
            'Q' => NumberFormat::FORMAT_NUMBER_00,
            'R' => NumberFormat::FORMAT_NUMBER_00,
            'S' => NumberFormat::FORMAT_NUMBER_00,
            'U' => NumberFormat::FORMAT_NUMBER_00,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getStyle('A1:U1')->applyFromArray(
                    [
                        'font' => array(
                            'name' => 'Calibri',
                            'size' => 12,
                            'bold' => true,
                        )
                    ]
                );

                $event->sheet->getStyle('A1:U100')->applyFromArray([
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'wrapText' => true,
                    ],
                ]);
            },
        ];
    }
}
