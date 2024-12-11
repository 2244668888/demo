<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BalanceSheetExport implements FromArray, ShouldAutoSize, WithStyles
{
    protected $nonCurrentAssets;
    protected $currentAssets;
    protected $nonCurrentLiabilities;
    protected $currentLiabilities;
    protected $equity;
    protected $totalAssets;
    protected $totalLiabilities;
    protected $totalEquity;
    protected $netIncome;

    public function __construct($nonCurrentAssets, $currentAssets, $nonCurrentLiabilities, $currentLiabilities, $equity, $totalAssets, $totalLiabilities, $totalEquity, $netIncome)
    {
        $this->nonCurrentAssets = $nonCurrentAssets;
        $this->currentAssets = $currentAssets;
        $this->nonCurrentLiabilities = $nonCurrentLiabilities;
        $this->currentLiabilities = $currentLiabilities;
        $this->equity = $equity;
        $this->totalAssets = $totalAssets;
        $this->totalLiabilities = $totalLiabilities;
        $this->totalEquity = $totalEquity;
        $this->netIncome = $netIncome;
    }

    public function array(): array
    {
        $data = [];

        $data[] = ['Balance Sheet'];  // This is the bold heading
        $data[] = [];

        // Non-Current Assets
        $data[] = ['Non-Current Assets', 'Amount'];
        foreach ($this->nonCurrentAssets as $asset) {
            $data[] = [$asset->name, number_format($asset->calculateBalanceDate(), 2)];
        }
        $data[] = ['Total Non-Current Assets', number_format($this->nonCurrentAssets->sum(function ($asset) {
            return $asset->calculateBalanceDate();
        }), 2)];
        $data[] = [];

        // Current Assets
        $data[] = ['Current Assets', 'Amount'];
        foreach ($this->currentAssets as $asset) {
            $data[] = [$asset->name, number_format($asset->calculateBalanceDate(), 2)];
        }
        $data[] = ['Total Current Assets', number_format($this->currentAssets->sum(function ($asset) {
            return $asset->calculateBalanceDate();
        }), 2)];
        $data[] = [];
        
        // Total Assets
        $data[] = ['Total Assets', number_format($this->totalAssets, 2)];
        $data[] = [];

        // Non-Current Liabilities
        $data[] = ['Non-Current Liabilities', 'Amount'];
        foreach ($this->nonCurrentLiabilities as $liability) {
            $data[] = [$liability->name, number_format($liability->calculateBalanceDate(), 2)];
        }
        $data[] = ['Total Non-Current Liabilities', number_format($this->nonCurrentLiabilities->sum(function ($liability) {
            return $liability->calculateBalanceDate();
        }), 2)];
        $data[] = [];

        // Current Liabilities
        $data[] = ['Current Liabilities', 'Amount'];
        foreach ($this->currentLiabilities as $liability) {
            $data[] = [$liability->name, number_format($liability->calculateBalanceDate(), 2)];
        }
        $data[] = ['Total Current Liabilities', number_format($this->currentLiabilities->sum(function ($liability) {
            return $liability->calculateBalanceDate();
        }), 2)];
        $data[] = [];

        // Total Liabilities
        $data[] = ['Total Liabilities', number_format($this->totalLiabilities, 2)];
        $data[] = [];

        // Equity
        $data[] = ['Equity', 'Amount'];
        foreach ($this->equity as $equityAccount) {
            $data[] = [$equityAccount->name, number_format($equityAccount->calculateBalanceDate(), 2)];
        }
        $data[] = ['Total Equity', number_format($this->totalEquity, 2)];
        $data[] = [];

        // Net Income
        $data[] = ['Net Income', number_format($this->netIncome, 2)];
        $data[] = [];

        // Balance Check
        $balanceCheck = $this->totalAssets === ($this->totalLiabilities + $this->totalEquity) ? 'Balanced' : 'Not Balanced';
        $data[] = ['Balance Check', $balanceCheck];

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