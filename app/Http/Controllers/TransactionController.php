<?php
namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Account;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function create($accountId)
    {
        $account = Account::findOrFail($accountId);
        $accounts = Account::where('id', '!=', $accountId)->get()->groupBy('type');
        return view('accounting.transactions.create', compact('account', 'accounts'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'account_id' => 'required|exists:accounts,id',
            'secondary_account_id' => 'required|exists:accounts,id',
            'type' => 'required|in:debit,credit',
            'amount' => 'required|numeric',
            'description' => 'nullable|string',
            'created_at' => 'required|date',
        ]);


        $transaction = Transaction::create($request->only(['account_id', 'type', 'amount', 'description', 'created_at']));
        $primaryAccount = Account::find($transaction->account_id);
        
        $secondaryTransactionType = $transaction->type == 'debit' ? 'credit' : 'debit';

        $secondaryAccount = Account::find($request->secondary_account_id);

        Transaction::create([
            'account_id' => $request->secondary_account_id,
            'type' => $secondaryTransactionType,
            'amount' => $transaction->amount,
            'description' => 'Counter entry for ' . $primaryAccount->name . ' to ' . $secondaryAccount->name,
            'created_at' => $transaction->created_at,
        ]); 
    
        return redirect()->route('accounts.index')->with('success', 'Transaction added successfully.');
    }
    


}
