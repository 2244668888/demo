<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BomReportExport implements FromCollection, WithHeadings, WithStyles
{
    use Exportable;

    protected $booms;

    public function __construct($booms)
    {
        $this->booms = $booms;
    }

    public function collection()
    {
        return collect($this->booms);
    }

    public function headings(): array
    {
        return [];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->mergeCells('A1:F1');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 16,
            ],
            
        ]);

        return [
            // Optionally style all rows or columns as needed
            1 => ['font' => ['bold' => true, 'size' => 16]],
        ];
    }
}