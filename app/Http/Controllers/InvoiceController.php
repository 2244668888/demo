<?php

namespace App\Http\Controllers;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\Outgoing;
use App\Models\SalePrice;
use App\Models\Account;
use App\Models\AccountCategory;
use App\Models\Transaction;
use App\Models\SstPercentage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class InvoiceController extends Controller
{

    public function Data(Request $request)
    {

        if ($request->ajax()) {

            $query = Invoice::select(
                'invoices.id',
                'invoices.outgoing_id',
                'invoices.date',
                'invoices.invoice_no',
                'invoices.created_by',
                'invoices.payment_status',
                'invoices.payment_voucher_no',
                'invoices.issue_date',
                'invoices.issued_by',
                'invoices.pv_status',

            )
            ->with(['outgoings', 'users', 'invoiceDetails', 'payments']);


// dd($request->all());

            $datatable = DataTables::eloquent($query)
                ->addIndexColumn()


                ->addColumn('created_by', function($row){
                    return $row->users->user_name ?? '-';
                })
                ->addColumn('action', function($row){
                    $totalAmount = $row->invoiceDetails->sum('incl_sst') ?? 0;
                    $remainingBalance = $totalAmount - $row->payments->sum('paying_amount');
                    $btn = '<div class="d-flex">
                                <a class="btn btn-success btn-sm mx-1" href="' . route('invoice.view', $row->id) .'"><i class="bi bi-eye"></i></a>
                                <a class="btn btn-danger btn-sm mx-1" href="' . route('invoice.preview', $row->id) .'" target="_blank"><i class="bi bi-file-pdf"></i></a>
                                <a class="btn btn-info btn-sm mx-1" href="' . route('invoice.edit', $row->id) .'"><i class="bi bi-pencil"></i></a>
                                <button class="btn btn-danger btn-sm mx-2" data-bs-toggle="modal" data-bs-target="#' . $row->id . '">
                                    <i class="bi bi-trash"></i>
                                </button>

                                <!-- Delete Modal -->
                                <div class="modal fade" id="' . $row->id . '" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel' . $row->id . '" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="staticBackdropLabel' . $row->id . '">Delete Problem</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                Are you sure you want to delete this problem?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <form method="POST" action="' . route('invoice.destroy', $row->id) . '">
                                                    ' . csrf_field() . '
                                                    ' . method_field('DELETE') . '
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>';
                    if ($row->payment_voucher_no != NULL && $row->pv_status == NULL) {
                            $btn .= '<a class="btn btn-warning btn-sm mx-1" href="' . route('invoice.payment_voucher_approve', $row->id) .'"  title="Approve Payment Voucher"><i class="bi bi-check"></i></a>';
                    }
                    if ($row->payment_voucher_no != NULL && $row->pv_status == "Not Approved") {
                            $btn .= '<a class="btn btn-warning btn-sm mx-1" href="' . route('invoice.payment_voucher_approve', $row->id) .'"  title="Approve Payment Voucher"><i class="bi bi-check"></i></a>';
                    }
                    if($row->pv_status == 'Approved'){
                        if (in_array($row->payment_status, ['due', 'partially_paid'])) {
                            $btn .= '<a class="btn btn-primary btn-sm mx-1" href="#" data-bs-toggle="modal" data-bs-target="#addPaymentModal" data-id="' . $row->id . '" data-total-amount="'. $totalAmount .'" data-remaining-balance="' . $remainingBalance . '" title="Add Payment"><i class="bi bi-cash-stack"></i></a>';
                        }
                        $btn .= '<a class="btn btn-secondary btn-sm mx-1" href="#" data-bs-toggle="modal" data-bs-target="#viewPaymentsModal" data-id="' . $row->id . '" title="View Payments"><i class="bi bi-currency-exchange"></i></a></div>';
                    }
                    

                    return $btn;
                })


                ->rawColumns(['action','outgoing_id','created_by'])
                // dd($request->search['value']);
                ->filterColumn('created_by', function($query, $keyword) {
                    $query->whereHas('users', function($q) use ($keyword) {
                        $q->where('users.user_name', 'like', "%{$keyword}%");
                    });
                });



                if($request->search['value'] == null ){

                    $datatable = $datatable->filter(function ($query) use ($request) {
                    if ($request->has('date') && !is_null($request->date)) {
                        $query->where('date', 'like', "%{$request->date}%");
                    }
                    if ($request->has('invoice_no') && !is_null($request->invoice_no)) {
                        $query->where('invoice_no', 'like', "%{$request->invoice_no}%");
                    }
                    if ($request->has('payment_status') && !is_null($request->payment_status)) {
                        $query->where('payment_status', 'like', "%{$request->payment_status}%");
                    }
                    if ($request->has('created_by') && !is_null($request->created_by)) {
                        $query->whereHas('users', function($q) use ($request) {
                            $q->where('users.user_name', 'like', "%{$request->created_by}%");
                        });
                    }

                });
            }

               return $datatable->make(true);
        }



    }
    public function index(){
        if (
            Auth::user()->hasPermissionTo('Invoice List') ||
            Auth::user()->hasPermissionTo('Invoice Create') ||
            Auth::user()->hasPermissionTo('Invoice Edit') ||
            Auth::user()->hasPermissionTo('Invoice View') ||
            Auth::user()->hasPermissionTo('Invoice Preview') ||
            Auth::user()->hasPermissionTo('Invoice Delete')
        ){
            $invoices = Invoice::all();
            $accounts = Account::with('category')->get();
            $bankCategory = AccountCategory::where('name', 'bank')->first();
            $bankCategoryId = $bankCategory ? $bankCategory->id : null;
            return view('erp.bd.invoice.index',compact ('invoices', 'accounts', 'bankCategoryId'));
        }
        return back()->with('custom_errors', 'You don`t have the right permission');
    }
    public function create(){
        if (!Auth::user()->hasPermissionTo('Invoice Create')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $sale_price = SalePrice::all();
        $sale_tax = SstPercentage::find(1);
        $outgoings = Outgoing::with(['outgoing_detail','outgoing_detail.product','outgoing_detail.product.units','sales_return.customer','purchase_return.supplier','order.customers'])->get();
        return view('erp.bd.invoice.create',compact('outgoings','sale_price','sale_tax'));
    }

    public function store(Request $request){
        if (!Auth::user()->hasPermissionTo('Invoice Create')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $validated = $request->validate([
            'outgoing_id' => [
                'required',
                Rule::unique('invoices', 'outgoing_id')->whereNull('deleted_at')
            ],
            'invoice_no' => [
                'required',
                Rule::unique('invoices', 'invoice_no')->whereNull('deleted_at')
            ],
            'products' => [
                'required'
            ],
            'term' => [
            'required',
        ]
        ]);

        $invoice = new Invoice();
        $invoice->outgoing_id = json_encode($request->outgoing_id);
        $invoice->invoice_no = $request->invoice_no;
        $invoice->date = $request->date;
        $invoice->acc_no = $request->acc_no;
        $invoice->term = $request->term;
        $invoice->created_by = Auth::user()->id;
        $invoice->payment_voucher_no = $request->payment_voucher_no;
        $invoice->issue_date = $request->issue_date;
        $invoice->issued_by = Auth::user()->id;
        $invoice->save();

        $totalRevenue = 0;

        foreach($request->products as $product){
            $invoice_detail = new InvoiceDetail();
            $invoice_detail->invoice_id = $invoice->id;
            $invoice_detail->product_id = $product['product_id'];
            $invoice_detail->qty = $product['qty'] ?? 0;
            $invoice_detail->price = $product['price'] ?? 0;
            $invoice_detail->disc = $product['disc'] ?? 0;
            $invoice_detail->excl_sst = $product['excl_sst'] ?? 0;
            $invoice_detail->sst = $product['sst'] ?? 0;
            $invoice_detail->incl_sst = $product['incl_sst'] ?? 0;
            $invoice_detail->save();

        }
        NotificationController::Notification('Invoice', 'Create', '' . route('invoice.view', $invoice->id) . '');

        return redirect()->route('invoice.index')->with('custom_success', 'Invoice Created Successfully.');
    }

    public function edit($id){
        if (!Auth::user()->hasPermissionTo('Invoice Edit')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $invoice = Invoice::find($id);
        $sale_price = SalePrice::all();
        $sale_tax = SstPercentage::find(1);
        $invoice_details = InvoiceDetail::where('invoice_id', $id)->get();
        $outgoings = Outgoing::with(['outgoing_detail','outgoing_detail.product','outgoing_detail.product.units','sales_return.customer','purchase_return.supplier','order.customers'])->get();
        return view('erp.bd.invoice.edit',compact('invoice','invoice_details','outgoings','sale_price','sale_tax'));
    }

    public function update(Request $request, $id){
        if (!Auth::user()->hasPermissionTo('Invoice Edit')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $validated = $request->validate([
            'outgoing_id' => [
                'required',
                Rule::unique('invoices', 'outgoing_id')->whereNull('deleted_at')->ignore($id)
            ],
            'invoice_no' => [
                'required',
                Rule::unique('invoices', 'invoice_no')->whereNull('deleted_at')->ignore($id)
            ],
            'products' => [
                'required'
            ]
        ]);

        $invoice = Invoice::find($id);
        $invoice->outgoing_id = $request->outgoing_id;
        $invoice->invoice_no = $request->invoice_no;
        $invoice->date = $request->date;
        $invoice->acc_no = $request->acc_no;
        $invoice->term = $request->term;
        $invoice->payment_status = 'due';
        $invoice->created_by = Auth::user()->id;
        $invoice->payment_voucher_no = $request->payment_voucher_no;
        $invoice->issue_date = $request->date;
        $invoice->issued_by = Auth::user()->id;
        $invoice->save();

        InvoiceDetail::where('invoice_id', $id)->delete();
        foreach($request->products as $product){
            $invoice_detail = new InvoiceDetail();
            $invoice_detail->invoice_id = $invoice->id;
            $invoice_detail->product_id = $product['product_id'];
            $invoice_detail->qty = $product['qty'] ?? 0;
            $invoice_detail->price = $product['price'] ?? 0;
            $invoice_detail->disc = $product['disc'] ?? 0;
            $invoice_detail->excl_sst = $product['excl_sst'] ?? 0;
            $invoice_detail->sst = $product['sst'] ?? 0;
            $invoice_detail->incl_sst = $product['incl_sst'] ?? 0;
            $invoice_detail->save();
        }
        return redirect()->route('invoice.index')->with('custom_success', 'Invoice Updated Successfully.');
    }

    public function view($id){
        if (!Auth::user()->hasPermissionTo('Invoice View')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $invoice = Invoice::find($id);
        $invoice_details = InvoiceDetail::where('invoice_id', $id)->get();
        $outgoings = Outgoing::with(['sales_return.customer','purchase_return.supplier','order.customers'])->get();
        return view('erp.bd.invoice.view',compact('invoice','invoice_details','outgoings'));
    }

    public function preview(Request $request, $id){
        if (!Auth::user()->hasPermissionTo('Invoice Preview')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $invoice = Invoice::find($id);
    // dd($invoice->outgoings->purchase_return->supplier);
        $invoice_details = InvoiceDetail::where('invoice_id', $id)->get();
        $price = InvoiceDetail::where('invoice_id', $id)->sum('price');
        $qty = InvoiceDetail::where('invoice_id', $id)->sum('qty');
        $disc = InvoiceDetail::where('invoice_id', $id)->sum('disc');
        $excl_sst = InvoiceDetail::where('invoice_id', $id)->sum('excl_sst');
        $sst = InvoiceDetail::where('invoice_id', $id)->sum('sst');
        $incl_sst = InvoiceDetail::where('invoice_id', $id)->sum('incl_sst');
        $incl_sst_words = $this->numberToWords($incl_sst);
        $sst_summary = SstPercentage::find(1);
        $pdf = FacadePdf::loadView('erp.bd.invoice.preview', compact('invoice', 'invoice_details', 'disc', 'excl_sst', 'sst', 'incl_sst', 'sst_summary','incl_sst_words'))->setPaper('a4');
        return $pdf->stream('invoice.preview');
    }

    function numberToWords($number) {
        $hyphen = '-';
        $conjunction = ' and ';
        $separator = ', ';
        $negative = 'negative ';
        $decimal = ' point ';
        $dictionary = array(
            0 => 'zero',
            1 => 'one',
            2 => 'two',
            3 => 'three',
            4 => 'four',
            5 => 'five',
            6 => 'six',
            7 => 'seven',
            8 => 'eight',
            9 => 'nine',
            10 => 'ten',
            11 => 'eleven',
            12 => 'twelve',
            13 => 'thirteen',
            14 => 'fourteen',
            15 => 'fifteen',
            16 => 'sixteen',
            17 => 'seventeen',
            18 => 'eighteen',
            19 => 'nineteen',
            20 => 'twenty',
            30 => 'thirty',
            40 => 'forty',
            50 => 'fifty',
            60 => 'sixty',
            70 => 'seventy',
            80 => 'eighty',
            90 => 'ninety',
            100 => 'hundred',
            1000 => 'thousand',
            1000000 => 'million',
            1000000000 => 'billion',
            1000000000000 => 'trillion',
            1000000000000000 => 'quadrillion',
            1000000000000000000 => 'quintillion'
        );

        if (!is_numeric($number)) {
            return false;
        }

        if (($number >= 0 && (int)$number < 0) || (int)$number < 0 - PHP_INT_MAX) {
            // overflow
            trigger_error('numberToWords only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX, E_USER_WARNING);
            return false;
        }

        if ($number < 0) {
            return $negative . $this->numberToWords(abs($number));
        }

        $string = $fraction = null;

        if (strpos($number, '.') !== false) {
            list($number, $fraction) = explode('.', $number);
        }

        switch (true) {
            case $number < 21:
                $string = $dictionary[$number];
                break;
            case $number < 100:
                $tens = ((int)($number / 10)) * 10;
                $units = $number % 10;
                $string = $dictionary[$tens];
                if ($units) {
                    $string .= $hyphen . $dictionary[$units];
                }
                break;
            case $number < 1000:
                $hundreds = $number / 100;
                $remainder = $number % 100;
                $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
                if ($remainder) {
                    $string .= $conjunction . $this->numberToWords($remainder);
                }
                break;
            default:
                $baseUnit = pow(1000, floor(log($number, 1000)));
                $numBaseUnits = (int)($number / $baseUnit);
                $remainder = $number % $baseUnit;
                $string = $this->numberToWords($numBaseUnits) . ' ' . $dictionary[$baseUnit];
                if ($remainder) {
                    $string .= $remainder < 100 ? $conjunction : $separator;
                    $string .= $this->numberToWords($remainder);
                }
                break;
        }

        if (null !== $fraction && is_numeric($fraction)) {
            $string .= $decimal;
            $words = array();
            foreach (str_split((string) $fraction) as $number) {
                $words[] = $dictionary[$number];
            }
            $string .= implode(' ', $words);
        }

        return $string;
    }

    public function paymentVoucherApprove($id){
        if (!Auth::user()->hasPermissionTo('Invoice View')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $invoice = Invoice::find($id);
        $invoice_details = InvoiceDetail::where('invoice_id', $id)->get();
        $outgoings = Outgoing::with(['sales_return.customer','purchase_return.supplier','order.customers'])->get();
        return view('erp.bd.invoice.paymentvoucher',compact('invoice','invoice_details','outgoings'));
    }

    public function paymentVouchercheck(Request $request, $id)
    {
        if (!Auth::user()->hasPermissionTo('Invoice View')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }

        $invoice = Invoice::find($id);
        if (!$invoice) {
            return back()->with('custom_errors', 'Invoice not found');
        }

        $status = $request->input('status');
        $invoice->pv_status = $status;
        $invoice->save();

            $message = ($status === 'Approved') 
            ? 'Invoice has been successfully marked as Approved!' 
            : 'Invoice has been successfully marked as Not Approved!';

        return redirect()->route('invoice.index')->with('custom_success', $message);

    }




    public function destroy(Request $request, $id){
        if (!Auth::user()->hasPermissionTo('Invoice Delete')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $invoice = Invoice::find($id);
        InvoiceDetail::where('invoice_id', $id)->delete();
        $invoice->delete();
        return redirect()->route('invoice.index')->with('custom_success', 'Invoice Deleted Successfully.');
    }
}
