<?php

namespace App\Http\Controllers;
use App\Exports\LedgerExport;
use App\Exports\LedgerSingleExport;
use App\Models\Account;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LedgerController extends Controller
{
    public function index(Request $request)
    {
        if(
            Auth::user()->hasPermissionTo('Account Ledger List') ||
            Auth::user()->hasPermissionTo('Ledger List')
        ){
            $startDate = null;
            $endDate = null;
    
            if ($request->filled('date_range')) {
                $dates = explode(' - ', $request->input('date_range'));
                $startDate = isset($dates[0]) ? \Carbon\Carbon::createFromFormat('d-m-Y', $dates[0])->format('Y-m-d') : null;
                $endDate = isset($dates[1]) ? \Carbon\Carbon::createFromFormat('d-m-Y', $dates[1])->format('Y-m-d') : null;
            }

            $accountIds = $request->input('accounts');

            $accounts = Account::with(['transactions' => function ($query) use ($startDate, $endDate) {
                if ($startDate) {
                    $query->whereDate('created_at', '>=', $startDate);
                }
                if ($endDate) {
                    $query->whereDate('created_at', '<=', $endDate);
                }
            }])->when($accountIds, function ($query) use ($accountIds) {
                return $query->whereIn('id', $accountIds);
            })->get();

            return view('accounting.ledger.index', compact('accounts'));
        }
        return back()->with(
            'custom_errors',
            'You don`t have the right permission'
        );
    }

    public function ledgerAccount(Request $request, $accountId)
    {
        if (!Auth::user()->hasPermissionTo('Account Ledger List')) {
            return back()->with(
                'custom_errors',
                'You don`t have the right permission'
            );
        }
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
    
        $account = Account::findOrFail($accountId);
        $transactions = $account->transactions()->when($startDate, function ($query) use ($startDate) {
            return $query->whereDate('created_at', '>=', $startDate);
        })->when($endDate, function ($query) use ($endDate) {
            return $query->whereDate('created_at', '<=', $endDate);
        })->orderBy('created_at')->get();
    
        return view('accounting.ledger.single-ledger', compact('account', 'transactions'));
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
        $accountIds = $request->input('accounts');

        $transactions = Account::with(['transactions' => function ($query) use ($startDate, $endDate) {
            if ($startDate) {
                $query->whereDate('created_at', '>=', $startDate);
            }
            if ($endDate) {
                $query->whereDate('created_at', '<=', $endDate);
            }
        }])->when($accountIds, function ($query) use ($accountIds) {
            return $query->whereIn('id', $accountIds);
        })->get();

        if ($format === 'pdf') {
            $pdf = Pdf::loadView('accounting.pdf.ledger', compact('transactions'));
            return $pdf->download('ledger.pdf');
        }

        if ($format === 'excel') {
            return Excel::download(new LedgerExport($transactions), 'ledger.xlsx');
        }

        return redirect()->back();
    }

    public function exportSingleLedger(Request $request, $accountId, $format)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $account = Account::findOrFail($accountId);
        $transactions = $account->transactions()->when($startDate, function ($query) use ($startDate) {
            return $query->whereDate('created_at', '>=', $startDate);
        })->when($endDate, function ($query) use ($endDate) {
            return $query->whereDate('created_at', '<=', $endDate);
        })->get();

        if ($format === 'pdf') {
            $pdf = Pdf::loadView('accounting.pdf.ledger-single', compact('transactions'));
            return $pdf->download('ledger.pdf');
        }

        if ($format === 'excel') {
            return Excel::download(new LedgerSingleExport($transactions), 'ledger.xlsx');
        }

        return redirect()->back();
    }

    
}

