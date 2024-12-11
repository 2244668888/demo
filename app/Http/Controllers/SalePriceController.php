<?php

namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\SalePrice;
use App\Models\SalePriceVerificationHistory;
use App\Models\Department;
use App\Models\Designation;
use App\Models\PurchasePrice;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SalePriceController extends Controller
{

    public function Data(Request $request)
    {

        if ($request->ajax()) {

            $query = SalePrice::select(
                'sale_prices.id',
                'sale_prices.product_id',
                'sale_prices.date',
                'sale_prices.price',
                'sale_prices.status',

            )
            ->with(['product','product.units']);

            $datatable = DataTables::eloquent($query)
                ->addIndexColumn()

                // ->addColumn('part_no', function($row){
                //     return $row->product->part_no ?? '-';
                // })
                // ->addColumn('part_name', function($row){
                //     return $row->product->part_name ?? '-';
                // })
                // ->addColumn('model', function($row){
                //     return $row->product->model ?? '-';
                // })
                // ->addColumn('variance', function($row){
                //     return $row->product->variance ?? '-';
                // })
                // ->addColumn('unit', function($row){
                //     return $row->product->units->name ?? '-';
                // })
                // // ->addColumn('date', function($row) {
                // //     return $row->date ? Carbon::parse($row->date)->format('d-m-Y') : '-';
                // // })
                ->addColumn('action', function($row){
                        if($row->status != 'verified' && $row->status != 'cancelled'){
                        $btn = '<div class="d-flex"><a class="btn btn-success btn-sm mx-2"
                                        href="' .  route('sale_price.view', $row->id) .'"><i class="bi bi-eye"></i></a>
                                       <a class="btn btn-info btn-sm" href="'. route('sale_price.edit', $row->id) .'"><i
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
                                                            <form method="POST" action="' . route('sale_price.destroy', $row->id) . '">
                                                                ' . csrf_field() . '
                                                                ' . method_field('DELETE') . '
                                                                <button type="submit" class="btn btn-danger">Delete</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <a class="btn btn-warning btn-sm mx-2"
                                                href="'. route('sale_price.verify', $row->id) .'"><i
                                                    class="bi bi-check-circle"></i></a><div>
                                            ';
                        return $btn;
                    }else{
                        $btn = '<div class="d-flex"><a class="btn btn-success btn-sm mx-2"
                                        href="' .  route('sale_price.view', $row->id) .'"><i class="bi bi-eye"></i></a>
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
                                                            <form method="POST" action="' . route('sale_price.destroy', $row->id) . '">
                                                                ' . csrf_field() . '
                                                                ' . method_field('DELETE') . '
                                                                <button type="submit" class="btn btn-danger">Delete</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div>';

                        return $btn;

                    }
                    })

                ->rawColumns(['action']);





                if($request->search['value'] == null ){

                    $datatable = $datatable->filter(function ($query) use ($request) {
                    if ($request->has('part_no') && !is_null($request->part_no)) {
                        $query->whereHas('product', function($q) use ($request) {
                            $q->where('products.part_no', 'like', "%{$request->part_no}%");
                        });
                    }
                    if ($request->has('part_name') && !is_null($request->part_name)) {
                        $query->whereHas('product', function($q) use ($request) {
                            $q->where('products.part_name', 'like', "%{$request->part_name}%");
                        });
                    }
                    if ($request->has('model') && !is_null($request->model)) {
                        $query->whereHas('product', function($q) use ($request) {
                            $q->where('products.model', 'like', "%{$request->model}%");
                        });
                    }
                    if ($request->has('variance') && !is_null($request->variance)) {
                        $query->whereHas('product', function($q) use ($request) {
                            $q->where('products.variance', 'like', "%{$request->variance}%");
                        });
                    }
                    if ($request->has('unit') && !is_null($request->unit)) {
                        $query->whereHas('product.units', function($q) use ($request) {
                            $q->where('name', 'like', "%{$request->unit}%");
                        });
                    }

                    if ($request->has('price') && !is_null($request->price)) {
                        $query->where('price', 'like', "%{$request->price}%");
                    }
                    if ($request->has('date') && !is_null($request->date)) {
                        $query->where('date', 'like', "%{$request->date}%");
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
            Auth::user()->hasPermissionTo('Sales Price List') ||
            Auth::user()->hasPermissionTo('Sales Price Create') ||
            Auth::user()->hasPermissionTo('Sales Price Edit') ||
            Auth::user()->hasPermissionTo('Sales Price Verify') ||
            Auth::user()->hasPermissionTo('Sales Price View') ||
            Auth::user()->hasPermissionTo('Sales Price Delete')
        ){
            $saleprices = SalePrice::with('product','product.units')->get();
            return view('erp.bd.saleprice.index',compact ('saleprices'));
        }
        return back()->with('custom_errors', 'You don`t have the right permission');
    }
    public function create(){
        if (!Auth::user()->hasPermissionTo('Sales Price Create')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $products = Product::with('type_of_products','units', 'categories')->get();
        return view('erp.bd.saleprice.create',compact('products'));
    }

    public function store(Request $request){
        if (!Auth::user()->hasPermissionTo('Sales Price Create')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }

        $validated = $request->validate([
            'product_id' => [
                'required'
            ],
            'price' => [
                'required'
            ],
            'date' => [
                'required'
            ]
          ]);

        $saleprice = new SalePrice();
        $saleprice->product_id = $request->product_id;
        $saleprice->price = $request->price;
        $saleprice->date = $request->date;
        $saleprice->save();
        NotificationController::Notification('Sales Price', 'Create', '' . route('sale_price.view', $saleprice->id) . '');
        return redirect()->route('sale_price.index')->with('custom_success', 'Sales Price Created Successfully.');
    }

    public function edit(Request $request, $id){
        if (!Auth::user()->hasPermissionTo('Sales Price Edit')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $saleprices = SalePrice::find($id);
        $products = Product::with('type_of_products','units', 'categories')->get();
        return view('erp.bd.saleprice.edit', compact('saleprices','products'));
    }

    public function getData(Request $request)
    {
        try {
            $purchase_price = SalePrice::where('product_id', $request->product_id)
                            ->orderBy('created_at', 'desc')
                            ->with(['product.type_of_products','product.categories','product.units'])
                            ->first();

            if (is_null($purchase_price)) {
                $purchase_price = Product::with(['type_of_products','categories','units'])->find($request->product_id);
            }

            return ['purchase_price'=>$purchase_price];

        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong', 'message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id){
        if (!Auth::user()->hasPermissionTo('Sales Price Edit')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $validated = $request->validate([
            'product_id' => [
                'required'
            ],
            'price' => [
                'required'
            ],
            'date' => [
                'required'
            ]
          ]);
        $saleprice = SalePrice::find($id);
        $saleprice->product_id = $request->product_id;
        $saleprice->price = $request->price;
        $saleprice->date = $request->date;
        $saleprice->save();
        return redirect()->route('sale_price.index')->with('custom_success', 'Sales Price Updated Successfully.');
    }

    public function verify($id){
        if (!Auth::user()->hasPermissionTo('Sales Price Verify')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $department = Department::find(Auth::user()->department_id);
        $designation = Designation::find(Auth::user()->designation_id);
        $products = Product::with('type_of_products','units', 'categories')->get();
        $saleprices = SalePrice::with(['product.type_of_products', 'product.units'])->find($id);
        return view('erp.bd.saleprice.verify',compact('saleprices','department','designation','products'));
    }

    public function verify_update(Request $request, $id){
        if (!Auth::user()->hasPermissionTo('Sales Price Verify')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $saleprice = SalePrice::find($id);
        $saleprice->verification_by = Auth::user()->id;
        $saleprice->status = 'verified';
        $saleprice->save();
        NotificationController::Notification('Sales Price', 'Verify', '' . route('sale_price.view', $saleprice->id) . '');
        return redirect()->route('sale_price.index')->with('custom_success', 'Sales Price has been Verified.');
    }

    public function decline_cancel(Request $request, $id){
        if (!Auth::user()->hasPermissionTo('Sales Price Verify')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $saleprice = SalePrice::find($id);
        $saleprice->verification_by = Auth::user()->id;
        $saleprice->status = ($request->decline_cancel == 'decline') ? 'declined' : 'cancelled';
        $saleprice->reason = $request->decline_cancel_reason ?? null;
        $saleprice->save();
        if($request->decline_cancel == 'decline'){
            return redirect()->route('sale_price.index')->with('custom_success', 'Sales Price has been Declined.');
        }
        return redirect()->route('sale_price.index')->with('custom_success', 'Sales Price has been Cancelled.');
    }


    public function verified(Request $request, $id){
        if (!Auth::user()->hasPermissionTo('Sales Price Verify')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $saleprice = SalePrice::find($id);
        $saleprice->status = $request->status;
        $saleprice->save();
        $saleprice_verification = new SalePriceVerificationHistory();
        $saleprice_verification->saleprice_id = $id;
        $saleprice_verification->status = $request->status;
        $saleprice_verification->date = Carbon::now();
        $saleprice_verification->approved_by = $request->approved_by;
        $saleprice_verification->department_id = $request->department_id;
        $saleprice_verification->designation_id = $request->designation_id;
        $saleprice_verification->save();
        return redirect()->route('sale_price.index')->with('custom_success', 'Sales Price Status Updated Successfully.');
    }

    public function declined(Request $request, $id){
        if (!Auth::user()->hasPermissionTo('Sales Price Verify')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $saleprice = SalePrice::find($id);
        $saleprice->status = $request->status;
        $saleprice->save();
        $saleprice_verification = new SalePriceVerificationHistory();
        $saleprice_verification->saleprice_id = $id;;
        $saleprice_verification->status = $request->status;
        $saleprice_verification->date = Carbon::now();
        $saleprice_verification->approved_by = $request->approved_by;
        $saleprice_verification->department_id = $request->department_id;
        $saleprice_verification->designation_id = $request->designation_id;
        $saleprice_verification->save();
        return redirect()->route('sale_price.index')->with('custom_success', 'Sales Price Updated Successfully.');
    }

    public function view(Request $request, $id){
        if (!Auth::user()->hasPermissionTo('Sales Price View')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $saleprices = SalePrice::find($id);
        $products = Product::with('type_of_products','units', 'categories')->get();
        $saleprice_verifications = SalePriceVerificationHistory::where('saleprice_id', $id)->get();
        return view('erp.bd.saleprice.view', compact('saleprices','products', 'saleprice_verifications'));
    }

    public function destroy(Request $request, $id){
        if (!Auth::user()->hasPermissionTo('Sales Price Delete')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $saleprices = SalePrice::find($id);
        $saleprices->delete();
        return redirect()->route('sale_price.index')->with('custom_success', 'Sales Price Deleted Successfully.');
    }

    public function get_Purchase_price(Request $request){
        $PurchasePrice = PurchasePrice::where('product_id',$request->product_id)->where('price', '>=', $request->price)->where('status', 'verified')->orderBy('created_at', 'desc')
        ->first();
        // $PurchasePrice = PurchasePrice::all();
        return ['PurchasePrice'=>$PurchasePrice];
    }
}
