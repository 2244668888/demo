<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountDashboardController extends Controller
{
    public function index()
    {
        if ( Auth::user()->hasPermissionTo('Account Dashboard')) {
            $accounts = Account::all();
            $totalIncome = Account::where('type', 'income')->get()->sum(function ($account) {
                return $account->calculateBalance();
            });

            $totalExpenses = Account::where('type', 'expense')->get()->sum(function ($account) {
                return $account->calculateBalance();
            });

            $totalAssets = Account::where('type', 'asset')->get()->sum(function ($account) {
                return $account->calculateBalance();
            });

            $totalLiabilitiesEquity = Account::whereIn('type', ['liability', 'equity'])->get()->sum(function ($account) {
                return $account->calculateBalance();
            });

            $today = Carbon::today();
            $todayIncome = Account::where('type', 'income')->whereHas('transactions', function($query) use ($today) {
                $query->whereDate('created_at', $today);
            })->get()->sum(function ($account) {
                return $account->calculateBalance();
            });

            $todayExpenses = Account::where('type', 'expense')->whereHas('transactions', function($query) use ($today) {
                $query->whereDate('created_at', $today);
            })->get()->sum(function ($account) {
                return $account->calculateBalance();
            });

            $currentMonth = Carbon::now()->month;
            $monthlyIncome = Account::where('type', 'income')->whereHas('transactions', function($query) use ($currentMonth) {
                $query->whereMonth('created_at', $currentMonth);
            })->get()->sum(function ($account) {
                return $account->calculateBalance();
            });

            $monthlyExpenses = Account::where('type', 'expense')->whereHas('transactions', function($query) use ($currentMonth) {
                $query->whereMonth('created_at', $currentMonth);
            })->get()->sum(function ($account) {
                return $account->calculateBalance();
            });

            $monthlyIncomeData = [];
            $monthlyExpensesData = [];
            $currentMonth = Carbon::now()->month;
            $currentYear = Carbon::now()->year;
            $daysInMonth = Carbon::now()->daysInMonth;
            
            for ($day = 1; $day <= $daysInMonth; $day++) {
                $date = Carbon::createFromDate($currentYear, $currentMonth, $day);
                $income = Account::where('type', 'income')->whereHas('transactions', function($query) use ($date) {
                    $query->whereDate('created_at', $date);
                })->get()->sum(function ($account) {
                    return $account->calculateBalance();
                });
            
                $expenses = Account::where('type', 'expense')->whereHas('transactions', function($query) use ($date) {
                    $query->whereDate('created_at', $date);
                })->get()->sum(function ($account) {
                    return $account->calculateBalance();
                });
                $monthlyIncomeData[] = $income;
                $monthlyExpensesData[] = $expenses;
            }
            

            return view('accounting.home', compact(
                'accounts', 
                'totalIncome', 
                'totalExpenses', 
                'totalAssets', 
                'totalLiabilitiesEquity',
                'todayIncome', 
                'todayExpenses', 
                'monthlyIncome', 
                'monthlyExpenses',
                'monthlyIncomeData',
                'monthlyExpensesData',
                'daysInMonth'
            ));
        }
        return back()->with(
            'custom_errors',
            'You don`t have the right permission'
        );
    }
}
