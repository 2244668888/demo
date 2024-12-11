<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Transaction;
use App\Models\Account;
use App\Models\PurchaseOrder;
use App\Models\Invoice;
use App\Models\Payroll;

class PaymentController extends Controller
{
    public function show($id)
    {
        $purchaseOrder = PurchaseOrder::with('payments.account')->find($id);
        $accounts = Account::all(); 
        return view('erp.pvd.purchase-order.index', compact('purchaseOrder', 'accounts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'purchase_order_id' => 'required',
            'paying_amount' => 'required|numeric|min:0',
            'payment_method' => 'required',
            'account_id' => 'required',
        ]);

        $inventoryAccount = Account::where('name', 'Inventory')->first();
        if (!$inventoryAccount) {
            return redirect()->back()->with('custom_errors', 'You must create an Inventory account first, e.g. Inventory');
        }

        $accountsPayableAccount = Account::where('name', 'Account Payable')->first();
        if (!$accountsPayableAccount) {
            return redirect()->back()->with('custom_errors', 'You must create an Account Payable first, e.g. Account Payable');
        }

        $purchase = PurchaseOrder::find($request->purchase_order_id);
        
        $totalAmount = $purchase->net_total;

        $totalPaid = $purchase->payments()->sum('paying_amount');
        $remainingBalance = $totalAmount - $totalPaid;
        if ($request->paying_amount > $remainingBalance) {
            return redirect()->back()->with('custom_errors', 'Paying amount exceeds the remaining balance.');
        }

        $payment = new Payment();
        $payment->purchase_order_id = $request->purchase_order_id;
        $payment->total_amount = $request->total_amount;
        $payment->paying_amount = $request->paying_amount;
        $payment->remaining_balance = $remainingBalance - $request->paying_amount;
        $payment->balance = $request->total_amount - $request->paying_amount;
        $payment->payment_method = $request->payment_method;
        $payment->account_id = $request->account_id;
        $payment->payment_note = $request->payment_note;
        $payment->save();

        if ($request->payment_method === 'cash') {
            $transaction = Transaction::create([
                'account_id' => $request->account_id,
                'type' => 'credit',
                'amount' => $request->paying_amount,
                'description' => 'Payment for PO #' . $request->purchase_order_id,
            ]);
        } elseif ($request->payment_method === 'bank') {
            $transaction = Transaction::create([
                'account_id' => $request->account_id,
                'type' => 'credit',
                'amount' => $request->paying_amount,
                'description' => 'Payment for PO #' . $request->purchase_order_id,
            ]);
        } elseif ($request->payment_method === 'credit') {
            $transaction = Transaction::create([
                'account_id' => $accountsPayableAccount->id,
                'type' => 'credit',
                'amount' => $request->paying_amount,
                'description' => 'Payment against PO #' . $request->purchase_order_id,
            ]);
        }
        Transaction::create([
            'account_id' => $inventoryAccount->id,
            'type' => 'debit',
            'amount' => $request->paying_amount,
            'description' => 'Increase inventory for PO #' . $request->purchase_order_id,
        ]);

        $purchaseOrder = PurchaseOrder::find($request->purchase_order_id);
        if ($payment->balance <= 0) {
            $purchaseOrder->payment_status = 'paid';
        } else {
            $purchaseOrder->payment_status = 'partially_paid';
        }
        $purchaseOrder->save();

        return redirect()->back()->with('success', 'Payment Added Successfully!');
    }


    public function getPayments($purchaseOrderId)
    {
        $payments = Payment::with(['account', 'purchaseOrder']) 
            ->where('purchase_order_id', $purchaseOrderId)
            ->get();
        
        return response()->json($payments);
    }

    public function showInvoicePayments($id)
    {
        $invoice = Invoice::with('payments.account')->find($id);
        $accounts = Account::all();
        return view('erp.pvd.invoice.index', compact('invoice', 'accounts'));
    }

    public function storeInvoicePayment(Request $request)
    {
        $validated = $request->validate([
            'invoice_id' => 'required',
            'paying_amount' => 'required|numeric|min:0',
            'payment_method' => 'required',
            'account_id' => 'required',
        ]);

        $inventoryAccount = Account::where('name', 'Inventory')->first();
        if (!$inventoryAccount) {
            return redirect()->back()->with('custom_errors', 'You must create an Inventory account first, e.g. Inventory');
        }

        $revenueAccount = Account::where('name', 'Revenue')->first();
        if (!$revenueAccount) {
            return redirect()->back()->with('custom_errors', 'You must create an Revenue account first, e.g. Revenue');
        }

        $invoice = Invoice::with('invoiceDetails')->find($request->invoice_id);
        
        $totalAmount = $invoice->invoiceDetails->sum('incl_sst');
        $totalPaid = $invoice->payments()->sum('paying_amount');
        $remainingBalance = $totalAmount - $totalPaid;

        if ($request->paying_amount > $remainingBalance) {
            return redirect()->back()->with('custom_errors', 'Paying amount exceeds the remaining balance.');
        }

        $payment = new Payment();
        $payment->invoice_id = $request->invoice_id;
        $payment->total_amount = $totalAmount;
        $payment->paying_amount = $request->paying_amount;
        $payment->remaining_balance = $remainingBalance - $request->paying_amount;
        $payment->balance = $remainingBalance - $request->paying_amount;
        $payment->payment_method = $request->payment_method;
        $payment->account_id = $request->account_id;
        $payment->payment_note = $request->payment_note;
        $payment->save();

        Transaction::create([
            'account_id' => $revenueAccount->id,
            'type' => 'credit',
            'amount' => $request->paying_amount,
            'description' => 'Revenue for Invoice #' . $request->invoice_id,
        ]);

        if ($request->payment_method === 'cash') {
            $transaction = Transaction::create([
                'account_id' => $request->account_id,
                'type' => 'debit',
                'amount' => $request->paying_amount,
                'description' => 'Cash payment for Invoice #' . $request->invoice_id,
            ]);
            Transaction::create([
                'account_id' => $inventoryAccount->id,
                'type' => 'credit',
                'amount' => $request->paying_amount,
                'description' => 'Decrease inventory for Invoice #' . $request->invoice_id,
            ]);
        } elseif ($request->payment_method === 'bank') {
            $transaction = Transaction::create([
                'account_id' => $request->account_id,
                'type' => 'debit',
                'amount' => $request->paying_amount,
                'description' => 'Bank payment for Invoice #' . $request->invoice_id,
            ]);
            Transaction::create([
                'account_id' => $inventoryAccount->id,
                'type' => 'credit',
                'amount' => $request->paying_amount,
                'description' => 'Decrease inventory for Invoice #' . $request->invoice_id,
            ]);
        } elseif ($request->payment_method === 'credit') {
            $transaction = Transaction::create([
                'account_id' => $accountsPayableAccount->id,
                'type' => 'credit',
                'amount' => $request->paying_amount,
                'description' => 'Payment against Invoice #' . $request->invoice_id,
            ]);
            Transaction::create([
                'account_id' => $inventoryAccount->id,
                'type' => 'credit',
                'amount' => $request->paying_amount,
                'description' => 'Decrease inventory for Invoice #' . $request->invoice_id,
            ]);
        }

        if ($remainingBalance - $request->paying_amount <= 0) {
            $invoice->payment_status = 'paid';
        } else {
            $invoice->payment_status = 'partially_paid';
        }
        $invoice->save();

        return redirect()->back()->with('success', 'Invoice Payment Added Successfully!');
    }

    public function getPaymentsForInvoice($invoiceId)
    {
        $payments = Payment::with(['account', 'invoice'])
            ->where('invoice_id', $invoiceId)
            ->get();
        return response()->json($payments);
    }

    public function showPayrollPayments($id)
    {
        $payroll = Payroll::with('payments.account')->find($id);
        $accounts = Account::all();
        return view('hr.payroll.index', compact('payroll', 'accounts'));
    }

    public function storePayrollPayment(Request $request)
    {
        $validated = $request->validate([
            'payroll_id' => 'required',
            'paying_amount' => 'required|numeric|min:0',
            'payment_method' => 'required',
            'account_id' => 'required',
        ]);


        $salaryExpenseAccount = Account::where('name', 'Salaries')->first();
        if (!$salaryExpenseAccount) {
            return redirect()->back()->with('custom_errors', 'You must create an Salaries account first, e.g. Salaries');
        }
        
        $payroll = Payroll::with('payrollDetails')->find($request->payroll_id);

        $totalAmount = $payroll->payrollDetails->sum('net_salary');
        $totalPaid = $payroll->payments()->sum('paying_amount');
        $remainingBalance = $totalAmount - $totalPaid;

        if ($request->paying_amount > $remainingBalance) {
            return redirect()->back()->with('error', 'Paying amount exceeds the remaining balance.');
        }

        $payment = new Payment();
        $payment->payroll_id = $request->payroll_id;
        $payment->total_amount = $totalAmount;
        $payment->paying_amount = $request->paying_amount;
        $payment->remaining_balance = $remainingBalance - $request->paying_amount;
        $payment->balance = $remainingBalance - $request->paying_amount;
        $payment->payment_method = $request->payment_method;
        $payment->account_id = $request->account_id;
        $payment->payment_note = $request->payment_note;
        $payment->save();

        Transaction::create([
            'account_id' => $salaryExpenseAccount->id,
            'type' => 'debit',
            'amount' => $request->paying_amount,
            'description' => 'Salary payment for Payroll #' . $request->payroll_id,
        ]);

        if ($request->payment_method === 'cash' || $request->payment_method === 'bank') {
            Transaction::create([
                'account_id' => $request->account_id,
                'type' => 'credit',
                'amount' => $request->paying_amount,
                'description' => ucfirst($request->payment_method) . ' payment for Payroll #' . $request->payroll_id,
            ]);
        }

        if ($remainingBalance - $request->paying_amount <= 0) {
            $payroll->payment_status = 'paid';
        } else {
            $payroll->payment_status = 'partially_paid';
        }

        $payroll->save();

        return redirect()->back()->with('success', 'Payroll Payment Added Successfully!');
    }

    public function getPaymentsForPayroll($payrollId)
    {
        $payments = Payment::with(['account', 'payment'])
            ->where('payroll_id', $payrollId)
            ->get();
        return response()->json($payments);
    }
}
