<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProfitLossExport implements FromArray, ShouldAutoSize, WithStyles
{
    protected $expenses;
    protected $income;
    protected $totalExpenses;
    protected $totalIncome;

    public function __construct($expenses, $income, $totalExpenses, $totalIncome)
    {
        $this->expenses = $expenses;
        $this->income = $income;
        $this->totalExpenses = $totalExpenses;
        $this->totalIncome = $totalIncome;
    }

    public function array(): array
    {
        $data = [];

        $data[] = ['Profit and Loss'];  // This is the bold heading
        $data[] = [];

        $data[] = ['Gross Expenses (Dr)'];
        $data[] = ['Expense', 'Amount'];

        foreach ($this->expenses as $account) {
            $data[] = [$account->name, number_format($account->calculateBalanceDate(), 2)];
        }

        $data[] = ['Total Gross Expenses', number_format($this->totalExpenses, 2)];
        $data[] = ['Gross Incomes (Cr)'];
        $data[] = ['Income', 'Amount'];

        foreach ($this->income as $account) {
            $data[] = [$account->name, number_format($account->calculateBalanceDate(), 2)];
        }

        $data[] = ['Total Gross Incomes', number_format($this->totalIncome, 2)];
        $data[] = ['Summary'];
        $data[] = ['Gross Loss C/D', number_format(max(0, $this->totalExpenses - $this->totalIncome), 2)];
        $data[] = ['Net Profit', number_format($this->totalIncome - $this->totalExpenses, 2)];

        return $data;
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
