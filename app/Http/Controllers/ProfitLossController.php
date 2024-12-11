<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\Account;
use App\Models\CarryForward;
use App\Exports\ProfitLossExport;
use Illuminate\Http\Request;
use Dompdf\Dompdf;
use Dompdf\Options;
use Maatwebsite\Excel\Facades\Excel;

class ProfitLossController extends Controller
{
    public function index(Request $request)
    {
        if(Auth::user()->hasPermissionTo('Profit Loss List')){
            $startDate = null;
            $endDate = null;
        
            if ($request->filled('date_range')) {
                $dates = explode(' - ', $request->input('date_range'));
                $startDate = isset($dates[0]) ? $dates[0] : null;
                $endDate = isset($dates[1]) ? $dates[1] : null;
            }
        
            $income = Account::where('type', 'income')->get();
            $expenses = Account::where('type', 'expense')->get();

            $totalIncome = $income->sum(function ($account) use ($startDate, $endDate) {
                return $account->calculateBalanceDate($startDate, $endDate);
            });

            $totalExpenses = $expenses->sum(function ($account) use ($startDate, $endDate) {
                return $account->calculateBalanceDate($startDate, $endDate);
            });
            $inventoryAccount = Account::where('name', 'inventory')->first();
            $totalInventoryBalance = $inventoryAccount ? $inventoryAccount->calculateBalanceDate($startDate, $endDate) : 0;
            $openingInventory = $inventoryAccount ? $inventoryAccount->opening_balance : 0;
            $cogs = $openingInventory + $totalInventoryBalance;

            $carryforward = CarryForward::where('year', date('Y'))->first();
            $carryforwardBalance = $carryforward ? $carryforward->balance : 0;

            return view('accounting.reports.profit_loss', compact('income', 'expenses', 'cogs', 'totalIncome', 'totalExpenses', 'carryforwardBalance'));
        }
        return back()->with(
            'custom_errors',
            'You don`t have the right permission'
        );
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
        $income = Account::where('type', 'income')->get();
        $expenses = Account::where('type', 'expense')->get();

        $totalIncome = $income->sum(function ($account) use ($startDate, $endDate) {
            return $account->calculateBalanceDate($startDate, $endDate);
        });

        $totalExpenses = $expenses->sum(function ($account) use ($startDate, $endDate) {
            return $account->calculateBalanceDate($startDate, $endDate);
        });

        $inventoryAccount = Account::where('name', 'Inventory')->first();
        $totalInventoryBalance = $inventoryAccount ? $inventoryAccount->calculateBalanceDate($startDate, $endDate) : 0;
        $openingInventory = $inventoryAccount ? $inventoryAccount->opening_balance : 0;
        $cogs = $openingInventory + $totalInventoryBalance;

        $carryforward = CarryForward::where('year', date('Y'))->first();
        $carryforwardBalance = $carryforward ? $carryforward->balance : 0;

        if ($format === 'pdf') {
            $options = new Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isPhpEnabled', true);
            $options->set('isRemoteEnabled', true);
            
            $dompdf = new Dompdf($options);
            $view = view('accounting.pdf.profit_loss', compact('income', 'expenses', 'totalIncome', 'cogs', 'totalExpenses', 'carryforwardBalance'))->render();
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
            return Excel::download(new ProfitLossExport($expenses, $income, $totalExpenses, $totalIncome, $carryforwardBalance, $cogs), 'profit_loss.xlsx');
        }

        return redirect()->back()->with('error', 'Invalid export format.');
    }
}
