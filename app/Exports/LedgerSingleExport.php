<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LedgerSingleExport implements FromCollection, WithHeadings, WithCustomStartCell, WithStyles
{
    protected $transactions;

    public function __construct($transactions)
    {
        $this->transactions = $transactions;
    }

    public function collection()
    {
        $data = [];
        $runningBalance = 0;
    
        foreach ($this->transactions as $tran) {
            if ($tran->type == 'debit') {
                $runningBalance += $tran->amount; 
            } else {
                $runningBalance -= $tran->amount; 
            }
    
            $data[] = [
                $tran->created_at->format('Y-m-d'),
                $tran->description,
                $tran->type == 'debit' ? $tran->amount : '',
                $tran->type == 'credit' ? $tran->amount : '',
                $runningBalance,
            ];
        }
    
        return collect($data);
    }
    

    public function startCell(): string
    {
        return 'A2';
    }

    public function headings(): array
    {
        return  [
            ['Ledger Summary'],
            ['Transaction Date', 'Ledger', 'Description', 'Debit', 'Credit', 'Balance'],
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            'A1' => [
                'font' => [
                    'bold' => true,
                    'size' => 20,
                ]
            ]
        ];
    }
}
