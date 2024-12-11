<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Designation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PurchasePrice;
use App\Models\Product;
use App\Models\PurchasePriceApprove;
use App\Models\SalePrice;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;

class PurchasePriceController extends Controller
{
    public function Data(Request $request)
    {
        if ($request->ajax()) {
            $query = PurchasePrice::select(
                'purchase_prices.id',
                'purchase_prices.product_id',
                'purchase_prices.date',
                'purchase_prices.price',
                'purchase_prices.status'
            )->with(['product', 'product.units']);

            $datatable = DataTables::eloquent($query)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    if ($row->status == 'submitted' || $row->status == 'declined') {
                        $btn =
                            '<div class="d-flex"><a class="btn btn-success btn-sm mx-2"
                                        href="' .
                            route('purchase_price.view', $row->id) .
                            '"><i class="bi bi-eye"></i></a>
                                       <a class="btn btn-info btn-sm" href="' .
                            route('purchase_price.edit', $row->id) .
                            '"><i
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
                                                        <form method="POST" action="' . route('purchase_price.destroy', $row->id) . '">
                                                            ' . csrf_field() . '
                                                            ' . method_field('DELETE') . '
                                                            <button type="submit" class="btn btn-danger">Delete</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                            <a class="btn btn-warning btn-sm mx-2"
                                                href="' .
                            route('purchase_price.verify', $row->id) .
                            '"><i
                                                    class="bi bi-check-circle"></i></a><div>
                                            ';
                        return $btn;
                    } else {
                        $btn =
                            '<div class="d-flex"><a class="btn btn-success btn-sm mx-2"
                                        href="' .
                            route('purchase_price.view', $row->id) .
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
                                                <form method="POST" action="' . route('purchase_price.destroy', $row->id) . '">
                                                    ' . csrf_field() . '
                                                    ' . method_field('DELETE') . '
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div><div>';

                        return $btn;
                    }
                })

                ->rawColumns(['action']);

            if ($request->search['value'] == null) {
                $datatable = $datatable->filter(function ($query) use (
                    $request
                ) {
                    if (
                        $request->has('part_no') &&
                        !is_null($request->part_no)
                    ) {
                        $query->whereHas('product', function ($q) use (
                            $request
                        ) {
                            $q->where(
                                'products.part_no',
                                'like',
                                "%{$request->part_no}%"
                            );
                        });
                    }
                    if (
                        $request->has('part_name') &&
                        !is_null($request->part_name)
                    ) {
                        $query->whereHas('product', function ($q) use (
                            $request
                        ) {
                            $q->where(
                                'products.part_name',
                                'like',
                                "%{$request->part_name}%"
                            );
                        });
                    }

                    if ($request->has('unit') && !is_null($request->unit)) {
                        $query->whereHas('product.units', function ($q) use (
                            $request
                        ) {
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
    public function index()
    {
        if (
            Auth::user()->hasPermissionTo('Purchase Price List') ||
            Auth::user()->hasPermissionTo('Purchase Price Create') ||
            Auth::user()->hasPermissionTo('Purchase Price Edit') ||
            Auth::user()->hasPermissionTo('Purchase Price Verify') ||
            Auth::user()->hasPermissionTo('Purchase Price View') ||
            Auth::user()->hasPermissionTo('Purchase Price Delete')
        ) {
            $PurchasePrices = PurchasePrice::with('product')->get();
            return view(
                'erp.pvd.purchase-price.index',
                compact('PurchasePrices')
            );
        }
        return back()->with(
            'custom_errors',
            'You don`t have the right permission'
        );
    }

    public function create()
    {
        if (!Auth::user()->hasPermissionTo('Purchase Price Create')) {
            return back()->with(
                'custom_errors',
                'You don`t have the right permission'
            );
        }
        $products = Product::with('type_of_products', 'units', 'categories')->get();
        return view('erp.pvd.purchase-price.create', compact('products'));
    }

    public function store(Request $request)
    {
        if (!Auth::user()->hasPermissionTo('Purchase Price Create')) {
            return back()->with(
                'custom_errors',
                'You don`t have the right permission'
            );
        }
        $validated = $request->validate([
            'product_id' => ['required'],
            'price' => ['required'],
            'date' => ['required'],
        ]);

        $purchaseprice = new PurchasePrice();
        $purchaseprice->product_id = $request->product_id;
        $purchaseprice->price = $request->price;
        $purchaseprice->date = $request->date;
        $purchaseprice->save();

        NotificationController::Notification('Purchase Price', 'Create', '' . route('purchase_price.view', $purchaseprice->id) . '');

        return redirect()
            ->route('purchase_price.index')
            ->with('success', 'Purchase Price Created Successfully.');
    }

    public function view(Request $request, $id)
    {
        if (!Auth::user()->hasPermissionTo('Purchase Price View')) {
            return back()->with(
                'custom_errors',
                'You don`t have the right permission'
            );
        }
        $purchaseprices = PurchasePrice::find($id);
        $products = Product::with('type_of_products', 'units', 'categories')->get();
        $purchase_prices_statuses = PurchasePriceApprove::where('purchase_price_id',$id)->get();

        return view(
            'erp.pvd.purchase-price.view',
            compact('purchaseprices', 'products','purchase_prices_statuses')
        );
    }

    public function edit($id)
    {
        if (!Auth::user()->hasPermissionTo('Purchase Price Edit')) {
            return back()->with(
                'custom_errors',
                'You don`t have the right permission'
            );
        }
        $products = Product::with('type_of_products', 'units', 'categories')->get();

        $purchase_price = PurchasePrice::with([
            'product.type_of_products',
            'product.units',
        ])->find($id);
        return view(
            'erp.pvd.purchase-price.edit',
            compact('purchase_price', 'products')
        );
    }

    public function getData(Request $request)
    {
        try {
            $purchase_price = PurchasePrice::where('product_id', $request->product_id)
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


    public function verify(Request $request, $id){
        if (!Auth::user()->hasPermissionTo('Purchase Price Verify')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }

        $pp = PurchasePrice::find($id);
        $pp->status = $request->status;
        $pp->save();

        $pp_verification = new PurchasePriceApprove();
        $pp_verification->purchase_price_id = $id;
        $pp_verification->status = $request->status;
        $pp_verification->date = Carbon::now();
        $pp_verification->approved_by = $request->approved_by;
        $pp_verification->department_id = $request->department_id;
        $pp_verification->designation_id = $request->designation_id;
        $pp_verification->save();
        return redirect()->route('purchase_price.index')->with('custom_success', 'Purchase Price Status Updated Successfully.');
    }

    public function decline(Request $request, $id){
        if (!Auth::user()->hasPermissionTo('Purchase Price Verify')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }

        $pp = PurchasePrice::find($id);
        $pp->status = $request->status;
        $pp->save();
        $pp_verification = new PurchasePriceApprove();
        $pp_verification->purchase_price_id = $id;
        $pp_verification->status = $request->status;
        $pp_verification->date = Carbon::now();
        $pp_verification->approved_by = $request->approved_by;
        $pp_verification->department_id = $request->department_id;
        $pp_verification->designation_id = $request->designation_id;
        $pp_verification->save();
        return redirect()->route('purchase_price.index')->with('custom_success', 'Purchase Price Status Updated Successfully.');
    }

    public function update(Request $request, $id)
    {
        if (!Auth::user()->hasPermissionTo('Purchase Price Edit')) {
            return back()->with(
                'custom_errors',
                'You don`t have the right permission'
            );
        }
        $validated = $request->validate([
            'price' => ['required'],
            'date' => ['required'],
        ]);
        $purchaseprice = PurchasePrice::find($id);
        $purchaseprice->product_id = $request->product_id;
        $purchaseprice->price = $request->price;
        $purchaseprice->date = $request->date;
        $purchaseprice->save();

        return redirect()
            ->route('purchase_price.index')
            ->with('success', 'Purchase Price updated Successfully.');
    }

    public function destroy($id)
    {
        if (!Auth::user()->hasPermissionTo('Purchase Price Delete')) {
            return back()->with(
                'custom_errors',
                'You don`t have the right permission'
            );
        }
        $purchaseprice = PurchasePrice::find($id);
        $purchaseprice->delete();
        return redirect()
            ->route('purchase_price.index')
            ->with('success', 'Purchase Price has been Deleted.');
    }

    public function verified($id)
    {
        if (!Auth::user()->hasPermissionTo('Purchase Price Verify')) {
            return back()->with(
                'custom_errors',
                'You don`t have the right permission'
            );
        }
        $department = Department::find(Auth::user()->department_id);
        $designation = Designation::find(Auth::user()->designation_id);
        $products = Product::with('type_of_products', 'units', 'categories')->get();
        $purchaseprice = PurchasePrice::with([
            'product.type_of_products',
            'product.units',
            'product.categories'
        ])->find($id);
        return view(
            'erp.pvd.purchase-price.verify',
            compact('purchaseprice', 'department', 'designation', 'products')
        );
    }

    // public function verify_update(Request $request, $id)
    // {
    //     if (!Auth::user()->hasPermissionTo('Purchase Price Verify')) {
    //         return back()->with(
    //             'custom_errors',
    //             'You don`t have the right permission'
    //         );
    //     }
    //     $purchaseprice = PurchasePrice::find($id);
    //     $purchaseprice->verification_by = Auth::user()->id;
    //     $purchaseprice->status = 'verified';
    //     $purchaseprice->save();
    //     NotificationController::Notification('Purchase Price', 'Verify', '' . route('purchase_price.view', $purchaseprice->id) . '');

    //     return redirect()
    //         ->route('purchase_price.index')
    //         ->with('custom_success', 'Purchase Price has been Verified.');
    // }

    // public function decline_cancel(Request $request, $id)
    // {
    //     if (!Auth::user()->hasPermissionTo('Purchase Price Verify')) {
    //         return back()->with(
    //             'custom_errors',
    //             'You don`t have the right permission'
    //         );
    //     }
    //     $purchaseprice = PurchasePrice::find($id);
    //     $purchaseprice->verification_by = Auth::user()->id;
    //     $purchaseprice->status =
    //         $request->decline_cancel == 'decline' ? 'declined' : 'cancelled';
    //     $purchaseprice->reason = $request->decline_cancel_reason ?? null;
    //     $purchaseprice->save();
    //     if ($request->decline_cancel == 'decline') {
    //         return redirect()
    //             ->route('purchase_price.index')
    //             ->with('custom_success', 'Purchase Price has been Declined.');
    //     }
    //     return redirect()
    //         ->route('purchase_price.index')
    //         ->with('custom_success', 'Purchase Price has been Cancelled.');
    // }

    public function get_Sale_price(Request $request)
    {
        $SalePrice = SalePrice::where('product_id', $request->product_id)
            ->where('price', '<=', $request->price)
            ->where('status', 'verified')
            ->orderBy('created_at', 'desc')
            ->first();
        return ['SalePrice'=>$SalePrice];
    }
}
