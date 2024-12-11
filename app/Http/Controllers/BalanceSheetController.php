<?php
namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\CarryForward;
use Illuminate\Http\Request;
use App\Exports\BalanceSheetExport;
use Illuminate\Support\Facades\Auth;
use Dompdf\Dompdf;
use Dompdf\Options;
use Maatwebsite\Excel\Facades\Excel;

class BalanceSheetController extends Controller
{
    public function index(Request $request)
    {
        if(Auth::user()->hasPermissionTo('Balance Sheet List')){
            $startDate = null;
            $endDate = null;
    
            if ($request->filled('date_range')) {
                $dates = explode(' - ', $request->input('date_range'));
                $startDate = isset($dates[0]) ? $dates[0] : null;
                $endDate = isset($dates[1]) ? $dates[1] : null;
            }
    
            $nonCurrentAssets = Account::where('type', 'asset')->where('categoryType', 'non-current')->get();
            $currentAssets = Account::where('type', 'asset')->where('categoryType', 'current')->get();
            $equity = Account::where('type', 'equity')->get();
            $nonCurrentLiabilities = Account::where('type', 'liability')->where('categoryType', 'non-current')->get();
            $currentLiabilities = Account::where('type', 'liability')->where('categoryType', 'current')->get();
            $income = Account::where('type', 'income')->get();
            $expenses = Account::where('type', 'expense')->get();
    
           
            $totalIncome = $income->sum(function ($account) use ($startDate, $endDate) {
                return $account->calculateBalanceDate($startDate, $endDate);
            });
    
            $totalExpenses = $expenses->sum(function ($account) use ($startDate, $endDate) {
                return $account->calculateBalanceDate($startDate, $endDate);
            });
    
            $netIncome = $totalIncome - $totalExpenses;
    
            $totalAssets = $nonCurrentAssets->sum(function ($account) use ($startDate, $endDate) {
                return $account->calculateBalanceDate($startDate, $endDate);
            }) + $currentAssets->sum(function ($account) use ($startDate, $endDate) {
                return $account->calculateBalanceDate($startDate, $endDate);
            });
    
            $totalLiabilities = $nonCurrentLiabilities->sum(function ($account) use ($startDate, $endDate) {
                return $account->calculateBalanceDate($startDate, $endDate);
            }) + $currentLiabilities->sum(function ($account) use ($startDate, $endDate) {
                return $account->calculateBalanceDate($startDate, $endDate);
            });
    
            $totalEquity = $equity->sum(function ($account) use ($startDate, $endDate) {
                return $account->calculateBalanceDate($startDate, $endDate);
            }) + $netIncome;
    
            $carryforward = CarryForward::where('year', date('Y'))->first();
            $carryforwardBalance = $carryforward ? $carryforward->balance : 0;
            $totalEquity = $equity->sum(function ($account) use ($startDate, $endDate) {
                return $account->calculateBalanceDate($startDate, $endDate);
            }) + $netIncome + $carryforwardBalance;
        
            $discrepancies = $this->findDiscrepancies($nonCurrentAssets, $currentAssets, $nonCurrentLiabilities, $currentLiabilities, $equity, $totalAssets, $totalLiabilities, $totalEquity);
    
            return view('accounting.reports.balance_sheet', compact('nonCurrentAssets', 'currentAssets',  'nonCurrentLiabilities', 'currentLiabilities',  'equity', 'totalAssets', 'totalLiabilities', 'totalEquity', 'discrepancies', 'netIncome', 'carryforwardBalance'));
        }
        return back()->with(
            'custom_errors',
            'You don`t have the right permission'
        );
    }

    private function findDiscrepancies($nonCurrentAssets, $currentAssets, $nonCurrentLiabilities, $currentLiabilities, $equity, $totalAssets, $totalLiabilities, $totalEquity)
    {
        $discrepancies = [];

        foreach ($nonCurrentAssets as $asset) {
            $balance = $asset->calculateBalance();
            if ($balance < 0) {
                $discrepancies[] = "Non-Current Asset '{$asset->name}' has a negative balance: " . number_format($balance, 2);
            }
        }

        foreach ($currentAssets as $asset) {
            $balance = $asset->calculateBalance();
            if ($balance < 0) {
                $discrepancies[] = "Current Asset '{$asset->name}' has a negative balance: " . number_format($balance, 2);
            }
        }

        foreach ($nonCurrentLiabilities as $liability) {
            $balance = $liability->calculateBalance();
            if ($balance < 0) {
                $discrepancies[] = "Non-Current Liability '{$liability->name}' has a negative balance: " . number_format($balance, 2);
            }
        }

        foreach ($currentLiabilities as $liability) {
            $balance = $liability->calculateBalance();
            if ($balance < 0) {
                $discrepancies[] = "Current Liability '{$liability->name}' has a negative balance: " . number_format($balance, 2);
            }
        }

        foreach ($equity as $equityAccount) {
            $balance = $equityAccount->calculateBalance();
            if ($balance < 0) {
                $discrepancies[] = "Equity '{$equityAccount->name}' has a negative balance: " . number_format($balance, 2);
            }
        }

        $expectedTotal = $totalLiabilities + $totalEquity;
        if ($totalAssets !== $expectedTotal) {
            $discrepancies[] = "Total Assets ({$totalAssets}) do not match Total Liabilities + Equity ({$expectedTotal}).";
            $discrepancies[] = "Please verify if all transactions have been recorded correctly or if any accounts are missing.";
        }

        return $discrepancies;
    }

    public function export(Request $request, $format)
    {
        $startDate = null;
        $endDate = null;

        if ($request->filled('date_range')) {
            $dates = explode(' - ', $request->input('date_range'));
            $startDate = isset($dates[0]) ? $dates[0] : null;
            $endDate = isset($dates[1]) ? $dates[1] : null;
        }

        $nonCurrentAssets = Account::where('type', 'asset')->where('categoryType', 'non-current')->get();
        $currentAssets = Account::where('type', 'asset')->where('categoryType', 'current')->get();
        $nonCurrentLiabilities = Account::where('type', 'liability')->where('categoryType', 'non-current')->get();
        $currentLiabilities = Account::where('type', 'liability')->where('categoryType', 'current')->get();
        $equity = Account::where('type', 'equity')->get();
        $income = Account::where('type', 'income')->get();
        $expenses = Account::where('type', 'expense')->get();

        $totalIncome = $income->sum(function ($account) use ($startDate, $endDate) {
            return $account->calculateBalanceDate($startDate, $endDate);
        });

        $totalExpenses = $expenses->sum(function ($account) use ($startDate, $endDate) {
            return $account->calculateBalanceDate($startDate, $endDate);
        });

        $netIncome = $totalIncome - $totalExpenses;

        $totalAssets = $nonCurrentAssets->sum(function ($account) use ($startDate, $endDate) {
            return $account->calculateBalanceDate($startDate, $endDate);
        }) + $currentAssets->sum(function ($account) use ($startDate, $endDate) {
            return $account->calculateBalanceDate($startDate, $endDate);
        });

        $totalLiabilities = $nonCurrentLiabilities->sum(function ($account) use ($startDate, $endDate) {
            return $account->calculateBalanceDate($startDate, $endDate);
        }) + $currentLiabilities->sum(function ($account) use ($startDate, $endDate) {
            return $account->calculateBalanceDate($startDate, $endDate);
        });

        $totalEquity = $equity->sum(function ($account) use ($startDate, $endDate) {
            return $account->calculateBalanceDate($startDate, $endDate);
        }) + $netIncome;

        $discrepancies = $this->findDiscrepancies($nonCurrentAssets, $currentAssets, $nonCurrentLiabilities, $currentLiabilities, $equity, $totalAssets, $totalLiabilities, $totalEquity);

        if ($format === 'pdf') {
            $options = new Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isPhpEnabled', true);
            $options->set('isRemoteEnabled', true);
            
            $dompdf = new Dompdf($options);
            $view = view('accounting.pdf.balance_sheet', compact('nonCurrentAssets', 'currentAssets', 'nonCurrentLiabilities', 'currentLiabilities', 'equity', 'totalAssets', 'totalLiabilities', 'totalEquity', 'discrepancies', 'netIncome'))->render();
            $dompdf->loadHtml($view);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
    
            // Force the total page count calculation
            $dompdf->getCanvas()->page_script(function ($pageNumber, $pageCount, $canvas) {
                $fontMetrics = new \Dompdf\FontMetrics($canvas, $canvas->get_dompdf()->getOptions());
                $font = $fontMetrics->getFont('Helvetica', 'normal');
                $size = 12;
                $canvas->text(180, 750, "Thank you for choosing ZENIG AUTO SDN BHD", $font, $size);
                $canvas->text(270, 770, "Page $pageNumber of $pageCount", $font, $size);
            });
            
            
    
            return $dompdf->stream('profit_loss.pdf');
        }

        if ($format === 'excel') {
            return Excel::download(new BalanceSheetExport($nonCurrentAssets, $currentAssets, $nonCurrentLiabilities, $currentLiabilities, $equity, $totalAssets, $totalLiabilities, $totalEquity, $netIncome), 'balance_sheet.xlsx');
        }

        return redirect()->back()->with('error', 'Invalid export format.');
    }

}
