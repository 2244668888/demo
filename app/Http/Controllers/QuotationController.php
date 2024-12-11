<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\InitailNo;
use App\Models\Product;
use App\Models\Quotation;
use App\Models\QuotationDetail;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class QuotationController extends Controller
{
    public function Data(Request $request)
    {

        if ($request->ajax()) {

            $query = Quotation::select(
                'quotations.id',
                'quotations.ref_no',
                'quotations.date',
                'quotations.customer_id',
                'quotations.status',

            )
            ->with(['customers']);

            $datatable = DataTables::eloquent($query)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                        if($row->status == 'verified'){
                        $btn = '<div class="d-flex"><a class="btn btn-info btn-sm mx-2" href="' .
                        route('quotation.preview', $row->id) .
                        '"><i class="bi bi-file-pdf"></i></a>
                        <a class="btn btn-success btn-sm mx-2" href="' .
                        route('quotation.view', $row->id) .
                        '"><i class="bi bi-eye"></i></a>

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
                                                <form method="POST" action="' . route('quotation.destroy', $row->id) . '">
                                                    ' . csrf_field() . '
                                                    ' . method_field('DELETE') . '
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div></div>';
                        return $btn;
                    }
                    elseif ($row->status == 'cancelled'){
                        $btn = '<div class="d-flex">
                        <a class="btn btn-success btn-sm mx-2" href="' .
                        route('quotation.view', $row->id) .
                        '"><i class="bi bi-eye"></i></a>

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
                                                <form method="POST" action="' . route('quotation.destroy', $row->id) . '">
                                                    ' . csrf_field() . '
                                                    ' . method_field('DELETE') . '
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div></div>';
                        return $btn;
                    }
                    else{
                        $btn = '<div class="d-flex"><a class="btn btn-info btn-sm " href="' .
                        route('quotation.preview', $row->id) .
                        '"><i class="bi bi-file-pdf"></i></a>
                        <a class="btn btn-success btn-sm mx-2" href="' .
                        route('quotation.view', $row->id) .
                        '"><i class="bi bi-eye"></i></a>
                          <a class="btn btn-info btn-sm" href="' . route('quotation.edit', $row->id) .'"><i
                                            class="bi bi-pencil"></i></a>
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
                                                <form method="POST" action="' . route('quotation.destroy', $row->id) . '">
                                                    ' . csrf_field() . '
                                                    ' . method_field('DELETE') . '
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>';
                        if ($row->status != 'verified' || $row->status != 'cancelled'){
                            $btn .='<a class="btn btn-warning btn-sm"
                                            href="' . route('quotation.verify', $row->id) .'"><i
                                                class="bi bi-check-circle"></i></a></div>';
                        }
                        return $btn;

                    }
                    })
                ->rawColumns(['action']);

                if($request->search['value'] == null ){

                    $datatable = $datatable->filter(function ($query) use ($request) {
                    if ($request->has('ref_no') && !is_null($request->ref_no)) {
                        $query->where('ref_no', 'like', "%{$request->ref_no}%");
                    }
                    if ($request->has('date') && !is_null($request->date)) {
                        $query->where('date', 'like', "%{$request->date}%");
                    }
                    if ($request->has('customer') && !is_null($request->customer)) {
                        $query->whereHas('customers', function($q) use ($request) {
                            $q->where('customers.name', 'like', "%{$request->customer}%");
                        });
                    }
                    if ($request->has('status') && !is_null($request->status)) {
                        $query->where('status', 'like', "%{$request->status}%");
                    }

                });
            }

               return $datatable->make(true);
        }


    }
    public function index(){
        if (
            Auth::user()->hasPermissionTo('Quotation List') ||
            Auth::user()->hasPermissionTo('Quotation Create') ||
            Auth::user()->hasPermissionTo('Quotation Edit') ||
            Auth::user()->hasPermissionTo('Quotation Verify') ||
            Auth::user()->hasPermissionTo('Quotation View') ||
            Auth::user()->hasPermissionTo('Quotation Delete')
        ){
            return view('erp.bd.quotation.index');
        }
        return back()->with('custom_errors', 'You don`t have the right permission');
    }
    public function create(){
        if (!Auth::user()->hasPermissionTo('Quotation Create')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $ref_no = '';
        $year = Carbon::now('Asia/Kuala_Lumpur')->format('y');
        $setting = InitailNo::where('screen', 'Quotation')->first();

        if ($setting) {
            $stock = Quotation::orderBy('id', 'DESC')->first();
            if ($stock) {
                // Extract running_no from $stock->ref_no which is in format 'SR/running_no/year'
                $parts = explode('/', $stock->ref_no);
                if (count($parts) == 3) {
                    $running_no = (int) $parts[1] + 1;
                } else {
                    $running_no = 1; // Fallback in case the format is unexpected
                }
                $ref_no = $setting->ref_no . '/' . $running_no . '/' . $year;
            } else {
                $ref_no = $setting->ref_no . '/' . $setting->running_no . '/' . $year;
            }
        } else {
            $stock = Quotation::orderBy('id', 'DESC')->first();
            if ($stock) {
                // Extract running_no from $stock->ref_no which is in format 'SR/running_no/year'
                $parts = explode('/', $stock->ref_no);
                if (count($parts) == 3) {
                    $running_no = (int) $parts[1] + 1;
                } else {
                    $running_no = 1; // Fallback in case the format is unexpected
                }
                $ref_no = 'QI/' . $running_no . '/' . $year;
            } else {
                $ref_no = 'QI/1/' . $year;
            }
        }
        $customers = Customer::all();
        $products = Product::with('type_of_products','units', 'categories')->get();
        return view('erp.bd.quotation.create',compact('products','customers','ref_no'));
    }

    public function store(Request $request){
        if (!Auth::user()->hasPermissionTo('Quotation Create')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }

        $validated = $request->validate([
            'customer_id' => [
                'required'
            ],
            'ref_no' => [
                'required'
            ],
            'date' => [
                'required'
            ],
            'products' => [
                'required'
            ]
        ]);

        $quotation = new Quotation();
        $quotation->customer_id = $request->customer_id;
        $quotation->ref_no = $request->ref_no;
        $quotation->date = Carbon::parse($request->date)->format('d-m-Y');
        $quotation->cc = $request->cc;
        $quotation->department = $request->department;
        $quotation->created_by = Auth::user()->id;
        $quotation->term_conditions = $request->term_conditions;
        $quotation->status = 'submitted';
        $quotation->save();

        foreach($request->products as $products){
            $quotation_detail = new QuotationDetail();
            $quotation_detail->quotation_id = $quotation->id;
            $quotation_detail->product_id = $products['product_id'];
            $quotation_detail->remarks = $products['remarks'];
            $quotation_detail->price = $products['price'];
            $quotation_detail->save();
        }
        NotificationController::Notification('Quotation', 'Create', '' . route('quotation.view', $quotation->id) . '');
        return redirect()->route('quotation.index')->with('custom_success', 'Quotation Created Successfully.');
    }

    public function edit(Request $request, $id){
        if (!Auth::user()->hasPermissionTo('Quotation Edit')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $quotation = Quotation::find($id);
        $customers = Customer::all();
        $products = Product::with('type_of_products','units', 'categories')->get();
        $quotation_details = QuotationDetail::where('quotation_id', $id)->get();
        return view('erp.bd.quotation.edit', compact('quotation','products','customers','quotation_details'));
    }

    public function update(Request $request, $id){
        if (!Auth::user()->hasPermissionTo('Quotation Edit')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $validated = $request->validate([
            'customer_id' => [
                'required'
            ],
            'ref_no' => [
                'required'
            ],
            'date' => [
                'required'
            ],
            'products' => [
                'required'
            ]
        ]);

        $quotation = Quotation::find($id);
        $quotation->customer_id = $request->customer_id;
        $quotation->ref_no = $request->ref_no;
        $quotation->date = Carbon::parse($request->date)->format('d-m-Y');
        $quotation->cc = $request->cc;
        $quotation->department = $request->department;
        $quotation->created_by = Auth::user()->id;
        $quotation->term_conditions = $request->term_conditions;
        $quotation->status = 'submitted';
        $quotation->save();

        QuotationDetail::where('quotation_id', $id)->delete();

        foreach($request->products as $products){
            $quotation_detail = new QuotationDetail();
            $quotation_detail->quotation_id = $quotation->id;
            $quotation_detail->product_id = $products['product_id'];
            $quotation_detail->remarks = $products['remarks'];
            $quotation_detail->price = $products['price'];
            $quotation_detail->save();
        }
        return redirect()->route('quotation.index')->with('custom_success', 'Quotation Updated Successfully.');
    }

    public function verify($id){
        if (!Auth::user()->hasPermissionTo('Quotation Verify')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $quotation = Quotation::find($id);
        $customers = Customer::all();
        $products = Product::with('type_of_products','units')->get();
        $quotation_details = QuotationDetail::where('quotation_id', $id)->get();
        return view('erp.bd.quotation.verify', compact('quotation','products','customers','quotation_details'));
    }

    public function verify_update(Request $request, $id){
        if (!Auth::user()->hasPermissionTo('Quotation Verify')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $quotation = Quotation::find($id);
        $quotation->verification_by = Auth::user()->id;
        $quotation->status = 'verified';
        $quotation->save();
        NotificationController::Notification('Quotation', 'Verify', '' . route('quotation.view', $quotation->id) . '');

        return redirect()->route('quotation.index')->with('custom_success', 'Quotation has been Verified.');
    }

    public function decline_cancel(Request $request, $id){
        if (!Auth::user()->hasPermissionTo('Quotation Verify')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $quotation = Quotation::find($id);
        $quotation->verification_by = Auth::user()->id;
        $quotation->status = ($request->decline_cancel == 'decline') ? 'declined' : 'cancelled';
        $quotation->reason = $request->decline_cancel_reason ?? null;
        $quotation->save();
        if($request->decline_cancel == 'decline'){
            return redirect()->route('quotation.index')->with('custom_success', 'Quotation has been Declined.');
        }
        return redirect()->route('quotation.index')->with('custom_success', 'Quotation has been Cancelled.');
    }

    public function view(Request $request, $id){
        if (!Auth::user()->hasPermissionTo('Quotation View')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $quotation = Quotation::find($id);
        $customers = Customer::all();
        $products = Product::with('type_of_products','units')->get();
        $quotation_details = QuotationDetail::where('quotation_id', $id)->get();
        return view('erp.bd.quotation.view', compact('quotation','products','customers','quotation_details'));
    }

    public function preview(Request $request, $id){
        $quotation = Quotation::find($id);
        $customers = Customer::all();
        $products = Product::with('type_of_products', 'units')->get();
        $quotation_details = QuotationDetail::where('quotation_id', $id)->get();

        $pdf = FacadePdf::loadView('erp.bd.quotation.preview', compact('quotation', 'products', 'customers', 'quotation_details'))->setPaper('a4');
        return $pdf->stream('quotation.preview');
    }

    public function destroy(Request $request, $id){
        if (!Auth::user()->hasPermissionTo('Quotation Delete')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $quotations = Quotation::find($id);
        QuotationDetail::where('quotation_id', $id)->delete();
        $quotations->delete();
        return redirect()->route('quotation.index')->with('custom_success', 'Quotation Deleted Successfully.');
    }
}
