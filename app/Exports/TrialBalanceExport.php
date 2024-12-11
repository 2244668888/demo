<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TrialBalanceExport implements FromCollection, WithHeadings, WithCustomStartCell, WithStyles
{
    protected $accounts;

    public function __construct($accounts)
    {
        $this->accounts = $accounts;
    }

    public function collection()
    {
        return $this->accounts->map(function ($account) {
            $debitTotal = $account->transactions()->where('type', 'debit')->sum('amount');
            $creditTotal = $account->transactions()->where('type', 'credit')->sum('amount');
            $closingBalance = $account->opening_balance + ($debitTotal - $creditTotal);

            return [
                'Account Name' => $account->name,
                'Type' => $account->type,
                'Opening Balance' => number_format($account->opening_balance, 2),
                'Debit Total' => number_format($debitTotal, 2),
                'Credit Total' => number_format($creditTotal, 2),
                'Closing Balance' => $closingBalance > 0 
                    ? 'Dr ' . number_format($closingBalance, 2) 
                    : 'Cr ' . number_format(abs($closingBalance), 2),
            ];
        });
    }

    public function startCell(): string
    {
        return 'A2';
    }

    public function headings(): array
    {
        return [
            ['Trial Balance'],
            ['Account Name',
            'Type',
            'Opening Balance',
            'Debit Total',
            'Credit Total',
            'Closing Balance'],
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
