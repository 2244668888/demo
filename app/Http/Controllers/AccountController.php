<?php
namespace App\Http\Controllers;

use App\Exports\AccountsExport; 
use App\Models\Account;
use App\Models\CarryForward;
use App\Models\AccountCategory;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade;
use PDF;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        if(
            Auth::user()->hasPermissionTo('Account List') ||
            Auth::user()->hasPermissionTo('Account Create') 
        ){
            $startDate = null;
            $endDate = null;
    
            if ($request->filled('date_range')) {
                $dates = explode(' - ', $request->input('date_range'));
                $startDate = isset($dates[0]) ? Carbon::parse($dates[0]) : null;
                $endDate = isset($dates[1]) ? Carbon::parse($dates[1]) : null;
            }

            $accountNames = Account::select('name')->distinct()->get()->pluck('name');
            $accountsQuery = Account::query();
            if ($request->filled('account_names')) {
                $accountsQuery->whereIn('name', $request->input('account_names'));
            }        
            if ($request->filled('type')) {
                $accountsQuery->where('type', $request->input('type'));
            }

            if ($startDate && $endDate) {
                $accountsQuery->whereBetween('created_at', [
                    $startDate->copy()->startOfDay(), 
                    $endDate->copy()->endOfDay()
                ]);
            }
            

            $accounts = $accountsQuery->get();
            return view('accounting.accounts.index', compact('accounts', 'accountNames')); 
        }
        return back()->with(
            'custom_errors',
            'You don`t have the right permission'
        );
        
    }

    public function exportExcel(Request $request)
    {
        $accountsQuery = Account::query();
        if ($request->filled('account_names')) {
            $accountsQuery->whereIn('name', $request->input('account_names'));
        }
        if ($request->filled('type')) {
            $accountsQuery->where('type', $request->input('type'));
        }

        if ($request->filled('date_range')) {
            $dates = explode(' - ', $request->input('date_range'));
            $startDate = isset($dates[0]) ? Carbon::parse($dates[0]) : null;
            $endDate = isset($dates[1]) ? Carbon::parse($dates[1]) : null;

            if ($startDate && $endDate) {
                $accountsQuery->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()]);
            }
        }

        $accounts = $accountsQuery->get();
        
        if ($accounts->isEmpty()) {
            $accounts = Account::all();
        }
    
        return Excel::download(new AccountsExport($accounts), 'accounts.xlsx');
    }

    public function exportPDF(Request $request)
    {
        $accountsQuery = Account::query();
        
        if ($request->filled('account_names')) {
            $accountsQuery->whereIn('name', $request->input('account_names'));
        }
        if ($request->filled('type')) {
            $accountsQuery->where('type', $request->input('type'));
        }

        if ($request->filled('date_range')) {
            $dates = explode(' - ', $request->input('date_range'));
            $startDate = isset($dates[0]) ? Carbon::parse($dates[0]) : null;
            $endDate = isset($dates[1]) ? Carbon::parse($dates[1]) : null;

            if ($startDate && $endDate) {
                $accountsQuery->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()]);
            }
        }

        $accounts = $accountsQuery->get();
        
        if ($accounts->isEmpty()) {
            $accounts = Account::all();
        }

        $pdf = PDF::loadView('accounting.accounts.pdf', compact('accounts'));
        return $pdf->download('accounts.pdf');
    }

    public function create()
    {
        if (!Auth::user()->hasPermissionTo('Account Create')) {
            return back()->with(
                'custom_errors',
                'You don`t have the right permission'
            );
        }
        $categories = AccountCategory::all();
        return view('accounting.accounts.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required',
            'name' => 'required',
            'type' => 'required',
            'opening_balance' => 'required|numeric',
            'category_id' => 'nullable|exists:account_categories,id',
            'categoryType' => 'required_if:type,asset,liability',
        ]);

        if (!$this->isBalanceSheetBalanced()) {
            return back()->with('custom_errors', 'The balance sheet is not balanced. Please correct any discrepancies before adding new transactions.');
        }

        $accountData = $request->only(['code', 'name', 'type', 'opening_balance']);
        if ($request->has('category_id') && $request->category_id != '') {
            $accountData['category_id'] = $request->category_id;
        }
        if (in_array($request->type, ['asset', 'liability'])) {
            $accountData['categoryType'] = $request->categoryType;
        }

        Account::create($accountData);

        return redirect()->route('accounts.index')->with('success', 'Account created successfully.');
    }

    private function isBalanceSheetBalanced()
    {
        $accounts = Account::all();
        $nonCurrentAssets = Account::where('type', 'asset')->where('categoryType', 'non-current')->get();
        $currentAssets = Account::where('type', 'asset')->where('categoryType', 'current')->get();
        $equity = Account::where('type', 'equity')->get();
        $nonCurrentLiabilities = Account::where('type', 'liability')->where('categoryType', 'non-current')->get();
        $currentLiabilities = Account::where('type', 'liability')->where('categoryType', 'current')->get();
        $income = Account::where('type', 'income')->get();
        $expenses = Account::where('type', 'expense')->get();

        $totalIncome = $income->sum(function ($account) {
            return $account->calculateBalance();
        });
    
        $totalExpenses = $expenses->sum(function ($account) {
            return $account->calculateBalance();
        });

        $netIncome = $totalIncome - $totalExpenses;

        $totalAssets = $nonCurrentAssets->sum(function ($account) {
            return $account->calculateBalance();
        }) + $currentAssets->sum(function ($account) {
            return $account->calculateBalance();
        });
    
        $totalLiabilities = $nonCurrentLiabilities->sum(function ($account) {
            return $account->calculateBalance();
        }) + $currentLiabilities->sum(function ($account) {
            return $account->calculateBalance();
        });
    
        $carryforward = CarryForward::where('year', date('Y'))->first();
        $carryforwardBalance = $carryforward ? $carryforward->balance : 0;
    
        $totalEquity = $equity->sum(function ($account) {
            return $account->calculateBalance();
        }) + $netIncome + $carryforwardBalance;
        return $totalAssets === ($totalLiabilities + $totalEquity);
    }


}
