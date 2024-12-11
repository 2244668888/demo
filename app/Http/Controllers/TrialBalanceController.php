<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TrialBalanceExport;
use Barryvdh\DomPDF\Facade;
use PDF;
use Illuminate\Support\Facades\Auth;

class TrialBalanceController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::user()->hasPermissionTo('Trial Balance List')) {
            
            $startDate = null;
            $endDate = null;

            if ($request->filled('date_range')) {
                $dates = explode(' - ', $request->input('date_range'));
                $startDate = isset($dates[0]) ? \Carbon\Carbon::createFromFormat('d-m-Y', $dates[0])->format('Y-m-d') : null;
                $endDate = isset($dates[1]) ? \Carbon\Carbon::createFromFormat('d-m-Y', $dates[1])->format('Y-m-d') : null;
            }

            $query = Account::with('category');

            if ($request->filled('account_names')) {
                $accountNames = $request->input('account_names');
                $query->whereIn('id', $accountNames);
            }

            if ($request->filled('account_type') && $request->input('account_type') !== '') {
                $query->where('type', $request->input('account_type'));
            }
            $accounts = $query->get();
            $groupedAccounts = [];

            foreach ($accounts as $account) {
                $transactionQuery = $account->transactions();
                if ($startDate && $endDate) {
                    $transactionQuery->whereBetween('created_at', [$startDate, $endDate]);
                }

                $debitTotal = $transactionQuery->where('type', 'debit')->sum('amount');
                $creditTotal = $transactionQuery->where('type', 'credit')->sum('amount');
                $openingBalance = $account->opening_balance;
                if (in_array($account->type, ['liability', 'equity', 'income'])) {
                    $closingBalance = $openingBalance + ($creditTotal - $debitTotal);
                } else {
                    $closingBalance = $openingBalance + ($debitTotal - $creditTotal);
                }
                
                $groupedAccounts[$account->type][] = [
                    'account' => $account,
                    'category' => $account->category ? $account->category->name : null,
                    'opening_balance' => $openingBalance,
                    'debit_total' => $debitTotal,
                    'credit_total' => $creditTotal,
                    'closing_balance' => $closingBalance,
                ];
            }
    
            $accountTypes = Account::select('type')->distinct()->get()->pluck('type');
            $accountNames = Account::select('id', 'name')->distinct()->get(); 
    
            return view('accounting.reports.trial_balance', compact('groupedAccounts', 'accountTypes', 'accountNames'));
        }
        return back()->with('custom_errors', 'You don’t have the right permission');
    }




    public function export(Request $request)
    {
        $query = Account::with('category');

        if ($request->filled('account_names')) {
            $accountNames = $request->input('account_names');
            $query->whereIn('id', $accountNames);
        }

        if ($request->filled('account_type') && $request->input('account_type') !== '') {
            $query->where('type', $request->input('account_type'));
        }
        $accounts = $query->get();
        return Excel::download(new TrialBalanceExport($accounts), 'trial_balance.xlsx');
    }


    public function downloadPdf(Request $request)
    {
        $query = Account::with('category');

        if ($request->filled('account_names')) {
            $accountNames = $request->input('account_names'); 
            $query->whereIn('id', $accountNames);
        }
        
        if ($request->filled('account_type') && $request->input('account_type') !== '') {
            $query->where('type', $request->input('account_type'));
        }
        $accounts = $query->get();
        $groupedAccounts = [];

        foreach ($accounts as $account) {
            $debitTotal = $account->transactions()->where('type', 'debit')->sum('amount');
            $creditTotal = $account->transactions()->where('type', 'credit')->sum('amount');
            $openingBalance = $account->opening_balance;
            $closingBalance = $openingBalance + ($debitTotal - $creditTotal);
            
            $groupedAccounts[$account->type][] = [
                'account' => $account,
                'category' => $account->category ? $account->category->name : null,
                'opening_balance' => $openingBalance,
                'debit_total' => $debitTotal,
                'credit_total' => $creditTotal,
                'closing_balance' => $closingBalance,
            ];
        }
    
        $pdf = \PDF::loadView('accounting.pdf.trial_balance', compact('groupedAccounts'));
        return $pdf->download('trial_balance.pdf');
    }
    
}
