<?php
namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\ExternalStatement;
use App\Models\Transaction;
use Illuminate\Http\Request;

class ReconciliationController extends Controller
{
    public function index($accountId)
    {
        $account = Account::findOrFail($accountId);
        $ledgerTransactions = $account->transactions;
        $externalStatements = ExternalStatement::where('account_id', $accountId)->get();
    
        return view('accounting.reconciliation.index', compact('account', 'ledgerTransactions', 'externalStatements'));
    } 
    
    public function storeExternalStatement(Request $request, $accountId)
    {
        $request->validate([
            'description' => 'required|string',
            'amount' => 'required|numeric',
            'type' => 'required|in:debit,credit',
            'transaction_date' => 'required|date',
        ]);

        ExternalStatement::create([
            'account_id' => $accountId,
            'description' => $request->input('description'),
            'amount' => $request->input('amount'),
            'type' => $request->input('type'),
            'transaction_date' => $request->input('transaction_date'),
        ]);

        return redirect()->route('reconciliation.index', $accountId)->with('success', 'External Statement added successfully.');
    }

    public function reconcile(Request $request, $accountId)
    {
        $transactionIds = $request->input('transactions', []); 
        $statementIds = $request->input('statements', []); 

        if (empty($transactionIds) && empty($statementIds)) {
            return redirect()->back()->with('warning', 'Please select transactions or statements to reconcile.');
        }

        $transactions = Transaction::whereIn('id', $transactionIds)->get();
        $statements = ExternalStatement::whereIn('id', $statementIds)->get();
        $messages = [];
        $success = true;

        foreach ($transactions as $transaction) {
            $matchingStatement = $statements->first(function($statement) use ($transaction) {
                return $statement->amount == $transaction->amount && $statement->type == $transaction->type;
            });

            if ($matchingStatement) {
                $transaction->update(['reconciled' => true]);
                $matchingStatement->update(['reconciled' => true]);
            } else {
                $success = false;
                $messages[] = "No matching external statement for transaction: {$transaction->description} of {$transaction->amount} {$transaction->type}.";
            }
        }

        if ($success) {
            return redirect()->back()->with('success', 'Reconciliation completed successfully.');
        } else {
            return redirect()->back()->with('warning', implode('<br>', $messages));
        }
    }
   
    
    
}
