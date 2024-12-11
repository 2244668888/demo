<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\CarryForward;
use App\Models\Account;
use Illuminate\Support\Facades\Auth;

class CarryForwardController extends Controller
{
    public function index()
    {
        if( Auth::user()->hasPermissionTo('Carryforward List')){
            return view('accounting.carryforward.index');
        }
        return back()->with(
            'custom_errors',
            'You don`t have the right permission'
        );
    }

    public function calculate($year)
{
    $income = Account::where('type', 'income')->with(['transactions' => function($query) use ($year) {
        $query->whereYear('created_at', $year);
    }])->get();

    $expenses = Account::where('type', 'expense')->with(['transactions' => function($query) use ($year) {
        $query->whereYear('created_at', $year);
    }])->get();

    $totalIncome = $income->sum(function ($account) {
        return $account->transactions->sum('amount');
    });

    $totalExpenses = $expenses->sum(function ($account) {
        return $account->transactions->sum('amount');
    });

    $balance = $totalIncome - $totalExpenses;
    $status = $balance >= 0 ? 'Profit' : 'Loss';

    return response()->json(['balance' => $balance, 'status' => $status]);
}


    public function store(Request $request)
    {
        $year = $request->input('year');
        $balance = $request->input('balance');
        $existingCarryforward = CarryForward::where('year', $year)->first();
    
        if ($existingCarryforward) {
            return response()->json(['error' => true, 'message' => 'You cannot update carryforward again, please recheck']);
        }
    
        $status = $balance >= 0 ? 'profit' : 'loss';
    
        CarryForward::create([
            'year' => $year,
            'balance' => $balance,
            'status' => $status 
        ]);
    
        return response()->json(['error' => false, 'message' => 'Carryforward saved successfully!']);
    }
    
}
