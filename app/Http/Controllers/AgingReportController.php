<?php

namespace App\Http\Controllers;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\Payment;
use App\Models\Outgoing;
use App\Models\SalePrice;
use App\Models\Account;
use App\Models\AccountCategory;
use App\Models\Transaction;
use App\Models\Customer;
use App\Models\Supplier;
use App\Models\SstPercentage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class AgingReportController extends Controller
{
    public function Data(Request $request)
    {
        if ($request->ajax()) {
            $query = Invoice::query()
                ->select('invoices.id', 'invoices.invoice_no', 'invoices.date', 'invoices.payment_voucher_no', 'invoices.payment_status', 'invoices.term', 'invoices.outgoing_id')
                ->when($request->customer_id, function ($q) use ($request) {
                    $q->where(function ($subQuery) use ($request) {
                        $subQuery->whereRaw(
                            'EXISTS (
                                SELECT 1 
                                FROM outgoings 
                                WHERE (invoices.outgoing_id LIKE CONCAT(\'%"\', outgoings.id, \'"%\'))
                                AND order_id IN (
                                    SELECT id FROM orders WHERE customer_id = ?
                                )
                            )', [$request->customer_id]
                        );
                    });
                })
                ->when($request->supplier_id, function ($q) use ($request) {
                    $q->where(function ($subQuery) use ($request) {
                        $subQuery->whereRaw(
                            'EXISTS (
                                SELECT 1 
                                FROM outgoings 
                                WHERE (invoices.outgoing_id LIKE CONCAT(\'%"\', outgoings.id, \'"%\'))
                                AND EXISTS (
                                    SELECT 1 
                                    FROM purchase_returns 
                                    WHERE purchase_returns.id = outgoings.pr_id
                                    AND supplier_id = ?
                                )
                            )', [$request->supplier_id]
                        );
                    });
                })
                ->when($request->sale_return, function ($q) use ($request) {
                    $q->where(function ($subQuery) use ($request) {
                        $subQuery->whereRaw(
                            'EXISTS (
                                SELECT 1 
                                FROM outgoings 
                                WHERE (invoices.outgoing_id LIKE CONCAT(\'%"\', outgoings.id, \'"%\'))
                                AND EXISTS (
                                    SELECT 1 
                                    FROM sale_returns 
                                    WHERE sale_returns.id = outgoings.sr_id
                                    AND customer_id = ?
                                )
                            )', [$request->sale_return]
                        );
                    });
                })
                ->when($request->payment_status, function ($q) use ($request) {
                    $q->where('payment_status', $request->payment_status);
                })
                ->with(['invoiceDetails']);
    
            return DataTables::eloquent($query)
                ->addIndexColumn()
                ->addColumn('total_amount', function ($row) {
                    // Calculate the total amount from invoice details
                    $totalAmount = $row->invoiceDetails->sum('incl_sst') ?? 0;
                    return number_format($totalAmount, 2);
                })
                ->addColumn('paying_amount', function ($row) {
                    // Sum up the paid amount from the payments table for this invoice
                    $paidAmount = Payment::where('invoice_id', $row->id)->sum('paying_amount');
                    return number_format($paidAmount, 2);
                })
                ->addColumn('outstanding_balance', function ($row) {
                    // Calculate the outstanding balance by subtracting paid from total
                    $totalAmount = $row->invoiceDetails->sum('incl_sst') ?? 0;
                    $paidAmount = Payment::where('invoice_id', $row->id)->sum('paying_amount');
                    $outstandingBalance = $totalAmount - $paidAmount;

                    // Also check for the latest remaining balance in payments
                    $latestPayment = Payment::where('invoice_id', $row->id)->latest()->first();
                    if ($latestPayment) {
                        $outstandingBalance = $latestPayment->remaining_balance;
                    }

                    return number_format($outstandingBalance, 2);
                })
                ->make(true);
        }
    }

    public function index()
    {
        if(Auth::user()->hasPermissionTo('Aging Report List')){
            $customers = Customer::all();
            $suppliers = Supplier::all();
            return view('accounting.reports.aging_report', compact('customers', 'suppliers'));
        }
        
        return back()->with('custom_errors', 'You don’t have the right permission');
    }
}
