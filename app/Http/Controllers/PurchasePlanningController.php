<?php

namespace App\Http\Controllers;

use App\Models\Bom;
use App\Models\InitailNo;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\PurchaseOrder;
use App\Models\PurchasePlanning;
use App\Models\PurchasePlanningDetail;
use App\Models\PurchasePlanningProduct;
use App\Models\PurchasePlanningSupplier;
use App\Models\PurchasePlanningVerification;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class PurchasePlanningController extends Controller
{
    public function Data(Request $request)
    {
        if ($request->ajax()) {

            // Base query with necessary relationships
            $query = PurchasePlanning::select(
                'purchase_plannings.id',
                'purchase_plannings.ref_no',
                'purchase_plannings.date',
                'purchase_plannings.created_by'
            )->with(['verificationOne', 'user', 'latestVerification.department']);
            // dd($query->get());
            // Initialize DataTable
            $datatable = DataTables::eloquent($query)
                ->addIndexColumn()

                // Add columns for relationships (if necessary)
                ->addColumn('user_name', function ($row) {
                    return $row->user->user_name ?? '-';
                })
                ->addColumn('verification_status', function ($row) {
                    return $row->latestVerification->status ?? '-';
                })
                ->addColumn('department_name', function ($row) {
                    return $row->latestVerification ? ($row->latestVerification->department->name ?? '-') : '-';
                })

                // Add action column for buttons
                ->addColumn('action', function ($row) {
                    $buttons = '<div class="d-flex">
                        <a class="btn btn-success btn-sm mx-1" href="' . route('purchase_planning.view', $row->id) . '" title="View"><i class="bi bi-eye"></i></a>';
                    // dd($row->purchase_planning_verifications);
                    $verification = $row->latestVerification;
                    if ($verification) {
                        $status = strtolower($verification->status);

                        if ($status === 'declined') {
                            $buttons .= '<a class="btn btn-info btn-sm mx-2" href="' . route('purchase_planning.edit', $row->id) . '" title="Edit"><i class="bi bi-pencil"></i></a>';
                            $buttons .= '<button class="btn btn-danger btn-sm mx-2" data-bs-toggle="modal" data-bs-target="#' . $row->id . '">
                                            <i class="bi bi-trash"></i>
                                        </button>

                                        <!-- Delete Modal -->
                                        <div class="modal fade" id="' . $row->id . '" data-bs-backdrop="static" data-bs-keyboard`="false" tabindex="-1" aria-labelledby="staticBackdropLabel' . $row->id . '" aria-hidden="true">
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
                                                        <form method="POST" action="' . route('purchase_planning.destroy', $row->id) . '">
                                                            ' . csrf_field() . '
                                                            ' . method_field('DELETE') . '
                                                            <button type="submit" class="btn btn-danger">Delete</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>';
                        }

                        if ($status === 'prepared') {
                            $buttons .= '<a class="btn btn-warning btn-sm mx-2" href="' . route('purchase_planning.verification', ['id' => $row->id, 'action' => 'check']) . '" title="Check"><i class="bi bi-check"></i></a><a class="btn btn-info btn-sm mx-2" href="' . route('purchase_planning.edit', $row->id) . '" title="Edit"><i class="bi bi-pencil"></i></a><a class="btn btn-danger btn-sm mx-2" href="' . route('purchase_planning.destroy', $row->id) . '" title="Delete"><i class="bi bi-trash"></i></a>';
                        }

                        if ($status === 'checked') {
                            $buttons .= '<a class="btn btn-info btn-sm mx-2" href="' . route('purchase_planning.verification', ['id' => $row->id, 'action' => 'verify_hod']) . '" title="Verify HOD"><i class="bi bi-check-circle"></i></a>';
                        }

                        if ($status === 'verified(hod)') {
                            $buttons .= '<a class="btn btn-info btn-sm mx-2" href="' . route('purchase_planning.verification', ['id' => $row->id, 'action' => 'verify_acc']) . '" title="Verify ACC"><i class="bi bi-check-circle"></i></a>';
                        }

                        if ($status === 'verified(acc)') {
                            $buttons .= '<a class="btn btn-primary btn-sm mx-2" href="' . route('purchase_planning.verification', ['id' => $row->id, 'action' => 'approve']) . '" title="Approve"><i class="bi bi-check-circle-fill"></i></a>';
                        }

                        if ($status === 'cancelled') {
                            $buttons .= '<a class="btn btn-danger btn-sm mx-2" href="' . route('purchase_planning.destroy', $row->id) . '" title="Delete"><i class="bi bi-trash"></i></a>';
                        }
                    }

                    return $buttons . '</div>';
                })

                // Customize date column format
                // ->editColumn('date', function($row) {
                //     return Carbon::parse($row->date)->format('d/m/Y');
                // })

                // Enable raw columns for action and status rendering
                ->rawColumns(['action', 'verification_status', 'department_name', 'user_name'])

                // Filter columns based on search inputs
                ->filterColumn('user_name', function ($query, $keyword) {
                    $query->whereHas('user', function ($q) use ($keyword) {
                        $q->where('user_name', 'like', "%{$keyword}%");
                    });
                })
                ->filterColumn('verification_status', function ($query, $keyword) {
                    $query->whereHas('latestVerification', function ($q) use ($keyword) {
                        $q->where('status', 'like', "%{$keyword}%");
                    });
                })
                ->filterColumn('department_name', function ($query, $keyword) {
                    $query->whereHas('latestVerification.department', function ($q) use ($keyword) {
                        $q->where('name', 'like', "%{$keyword}%");
                    });
                });


            if ($request->search['value'] == null) {

                $datatable = $datatable->filter(function ($query) use ($request) {
                    if ($request->has('ref_no') && !is_null($request->ref_no)) {
                        $query->where('ref_no', 'like', "%{$request->ref_no}%");
                    }
                    if ($request->has('date') && !is_null($request->date)) {
                        $query->where('date', 'like', "%{$request->date}%");
                    }
                    if ($request->has('status') && !is_null($request->status)) {
                        $query->whereHas('latestVerification', function ($q) use ($request) {
                            $q->where('status', 'like', "%{$request->status}%");
                        });
                    }
                    if ($request->has('user') && !is_null($request->user)) {
                        $query->whereHas('user', function ($q) use ($request) {
                            $q->where('users.user_name', 'like', "%{$request->user}%");
                        });
                    }
                    if ($request->has('department') && !is_null($request->department)) {
                        $query->whereHas('latestVerification.department', function ($q) use ($request) {
                            $q->where('departments.name', 'like', "%{$request->department}%");
                        });
                    }
                });
            }

            return $datatable->make(true);
        }
    }
    public function index()
    {
        if (
            Auth::user()->hasPermissionTo('Purchase Planning List') ||
            Auth::user()->hasPermissionTo('Purchase Planning Create') ||
            Auth::user()->hasPermissionTo('Purchase Planning Edit') ||
            Auth::user()->hasPermissionTo('Purchase Planning Verify HOD') ||
            Auth::user()->hasPermissionTo('Purchase Planning Verify ACC') ||
            Auth::user()->hasPermissionTo('Purchase Planning Approve') ||
            Auth::user()->hasPermissionTo('Purchase Planning Check') ||
            Auth::user()->hasPermissionTo('Purchase Planning View') ||
            Auth::user()->hasPermissionTo('Purchase Planning Delete')
        ) {
            $purchase_plannings = PurchasePlanning::all();
            return view('erp.pvd.purchase-planning.index', compact('purchase_plannings'));
        }
        return back()->with('custom_errors', 'You don`t have the right permission');
    }

    public function create()
    {
        if (!Auth::user()->hasPermissionTo('Purchase Planning Create')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $ref_no = '';
        $year = Carbon::now('Asia/Kuala_Lumpur')->format('Y');
        $setting = InitailNo::where('screen', 'Purchase Planning')->first();

        if ($setting) {
            $stock = PurchasePlanning::orderBy('id', 'DESC')->first();
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
            $stock = PurchasePlanning::orderBy('id', 'DESC')->first();
            if ($stock) {
                // Extract running_no from $stock->ref_no which is in format 'SR/running_no/year'
                $parts = explode('/', $stock->ref_no);
                if (count($parts) == 3) {
                    $running_no = (int) $parts[1] + 1;
                } else {
                    $running_no = 1; // Fallback in case the format is unexpected
                }
                $ref_no = 'PP/' . $running_no . '/' . $year;
            } else {
                $ref_no = 'PP/1/' . $year;
            }
        }
        $suppliers = Supplier::select('id', 'name')->get();
        $orders = Order::with('order_detail', 'user', 'customers', 'order_detail.products', 'order_detail.products.type_of_products')->get();
        return view('erp.pvd.purchase-planning.create', compact('orders', 'ref_no', 'suppliers'));
    }

    public function bom_get(Request $request)
    {
        // Fetch product IDs based on the order detail ID
        $product_ids = OrderDetail::where('order_id', $request->id)->pluck('product_id');

        $bom_data = [];
        foreach ($product_ids as $product_id) {
            $bom_tree = BomController::loadBomTree($product_id);

            if ($bom_tree) {
                $this->calculateUsedQty($bom_tree['subParts']);
            }

            $bom_data[] = $bom_tree;
        }
        return response()->json($bom_data);
    }

    private function calculateUsedQty(&$subParts)
    {
        foreach ($subParts as &$subPart) {
            $totalUsedQty = $subPart['subPart']->product->locations->sum('used_qty');
            $subPart['subPart']->used_qty = $totalUsedQty;

            // Recursively calculate used_qty for nested subParts
            if (!empty($subPart['bomTree']['subParts'])) {
                $this->calculateUsedQty($subPart['bomTree']['subParts']);
            }
        }
    }

    public function store(Request $request)
    {
        if (!Auth::user()->hasPermissionTo('Purchase Planning Create')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }

        $validated = $request->validate([
            'order_id' => [
                'required'
            ],
            'date' => [
                'required'
            ],
            'products' => [
                'required'
            ],
            'planning' => [
                'required'
            ]
        ]);

        $purchase_planning = new PurchasePlanning();
        $purchase_planning->order_id = $request->order_id;
        $purchase_planning->ref_no = $request->ref_no;
        $purchase_planning->date = $request->date;
        $purchase_planning->created_by = Auth::user()->id;
        $purchase_planning->save();

        foreach ($request->products as $products) {
            $purchase_planning_product = new PurchasePlanningProduct();
            $purchase_planning_product->purchase_planning_id = $purchase_planning->id;
            $purchase_planning_product->product_id = $products['product_id'] ?? null;
            $purchase_planning_product->product_qty = $products['product_qty'] ?? 0;
            $purchase_planning_product->total_qty = $products['total_qty'] ?? 0;
            $purchase_planning_product->save();
        }

        foreach ($request->planning as $planning) {
            $purchase_planning_detail = new PurchasePlanningDetail();
            $purchase_planning_detail->purchase_planning_id = $purchase_planning->id;
            $purchase_planning_detail->product_id = $planning['product_id'] ?? null;
            $purchase_planning_detail->total_qty = $planning['total_qty'] ?? 0;
            $purchase_planning_detail->inventory_qty = $planning['inventory_qty'] ?? 0;
            $purchase_planning_detail->balance = $planning['balance'] ?? 0;
            $purchase_planning_detail->moq = $planning['moq'] ?? 0;
            $purchase_planning_detail->planning_qty = $planning['planning_qty'] ?? 0;
            $purchase_planning_detail->save();
        }

        $storedData = json_decode($request->input('details'), true);

        $newArray = collect($storedData)->flatMap(function ($subArray) {
            return $subArray;
        })->sortBy('hiddenId')->values()->toArray();

        foreach ($newArray as $key => $value) {
            $detail = new PurchasePlanningSupplier();
            $detail->purchase_planning_id = $purchase_planning->id;
            $detail->product_id = $value['hiddenId'] ?? null;
            $detail->supplier_id = $value['supplier'] ?? null;
            $detail->qty = $value['qty'] ?? 0;
            $detail->save();
        }

        $status = new PurchasePlanningVerification();
        $status->purchase_planning_id = $purchase_planning->id;
        $status->user_id = Auth::user()->id;
        $status->status = 'prepared';
        $status->date = Carbon::now('Asia/Kuala_Lumpur')->format('d-m-Y h:i:s A');
        $status->department_id = Auth::user()->department_id ?? null;
        $status->designation_id = Auth::user()->designation_id ?? null;
        $status->save();


        NotificationController::Notification('Purchase Planning', 'Create', '' . route('purchase_planning.view', $purchase_planning->id) . '');


        return redirect()->route('purchase_planning.index')->with('custom_success', 'Purchase Planning has been Created Successfully!');
    }

    public function edit($id)
    {
        if (!Auth::user()->hasPermissionTo('Purchase Planning Edit')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $purchase_planning = PurchasePlanning::find($id);
        $purchase_planning_product = PurchasePlanningProduct::where('purchase_planning_id', $id)->get();
        $purchase_planning_detail = PurchasePlanningDetail::where('purchase_planning_id', $id)->get();
        $supplier_details = PurchasePlanningSupplier::where('purchase_planning_id', $id)->get();
        $suppliers = Supplier::select('id', 'name')->get();
        $orders = Order::with('order_detail', 'user', 'customers', 'order_detail.products', 'order_detail.products.type_of_products')->get();
        return view('erp.pvd.purchase-planning.edit', compact('orders', 'suppliers', 'purchase_planning', 'purchase_planning_product', 'purchase_planning_detail', 'supplier_details'));
    }

    public function update(Request $request, $id)
    {
        if (!Auth::user()->hasPermissionTo('Purchase Planning Edit')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }

        $validated = $request->validate([
            'date' => [
                'required'
            ],
            'planning' => [
                'required'
            ]
        ]);

        $purchase_planning = PurchasePlanning::find($id);
        $purchase_planning->date = $request->date;
        $purchase_planning->created_by = Auth::user()->id;
        $purchase_planning->save();

        PurchasePlanningDetail::where('purchase_planning_id', $id)->delete();
        foreach ($request->planning as $planning) {
            $purchase_planning_detail = new PurchasePlanningDetail();
            $purchase_planning_detail->purchase_planning_id = $purchase_planning->id;
            $purchase_planning_detail->product_id = $planning['product_id'] ?? null;
            $purchase_planning_detail->total_qty = $planning['total_qty'] ?? 0;
            $purchase_planning_detail->inventory_qty = $planning['inventory_qty'] ?? 0;
            $purchase_planning_detail->balance = $planning['balance'] ?? 0;
            $purchase_planning_detail->moq = $planning['moq'] ?? 0;
            $purchase_planning_detail->planning_qty = $planning['planning_qty'] ?? 0;
            $purchase_planning_detail->save();
        }

        $storedData = json_decode($request->input('details'), true);

        $newArray = collect($storedData)->flatMap(function ($subArray) {
            return $subArray;
        })->sortBy('hiddenId')->values()->toArray();

        PurchasePlanningSupplier::where('purchase_planning_id', $id)->delete();
        foreach ($newArray as $key => $value) {
            $detail = new PurchasePlanningSupplier();
            $detail->purchase_planning_id = $purchase_planning->id;
            $detail->product_id = $value['hiddenId'] ?? null;
            $detail->supplier_id = $value['supplier'] ?? null;
            $detail->qty = $value['qty'] ?? 0;
            $detail->save();
        }

        $status = new PurchasePlanningVerification();
        $status->purchase_planning_id = $purchase_planning->id;
        $status->user_id = Auth::user()->id;
        $status->status = 'prepared';
        $status->date = Carbon::now('Asia/Kuala_Lumpur')->format('d-m-Y h:i:s A');
        $status->department_id = Auth::user()->department_id ?? null;
        $status->designation_id = Auth::user()->designation_id ?? null;
        $status->save();

        return redirect()->route('purchase_planning.index')->with('custom_success', 'Purchase Planning has been Updated Successfully!');
    }

    public function view($id)
    {
        if (!Auth::user()->hasPermissionTo('Purchase Planning View')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $purchase_planning = PurchasePlanning::find($id);
        $purchase_planning_product = PurchasePlanningProduct::where('purchase_planning_id', $id)->get();
        $purchase_planning_detail = PurchasePlanningDetail::where('purchase_planning_id', $id)->get();
        $supplier_details = PurchasePlanningSupplier::where('purchase_planning_id', $id)->get();
        $verification_details = PurchasePlanningVerification::where('purchase_planning_id', $id)->get();
        $suppliers = Supplier::select('id', 'name')->get();
        $orders = Order::with('order_detail', 'user', 'customers', 'order_detail.products', 'order_detail.products.type_of_products')->get();
        return view('erp.pvd.purchase-planning.view', compact('orders', 'suppliers', 'purchase_planning', 'purchase_planning_product', 'purchase_planning_detail', 'supplier_details', 'verification_details'));
    }

    public function verification($id, $action)
    {
        $purchase_planning = PurchasePlanning::find($id);
        $purchase_planning_product = PurchasePlanningProduct::where('purchase_planning_id', $id)->get();
        $purchase_planning_detail = PurchasePlanningDetail::where('purchase_planning_id', $id)->get();
        $purchase_planning_verifications = PurchasePlanningVerification::where('purchase_planning_id', $id)->get();
        $supplier_details = PurchasePlanningSupplier::where('purchase_planning_id', $id)->get();
        $suppliers = Supplier::select('id', 'name')->get();
        $orders = Order::with('order_detail', 'user', 'customers', 'order_detail.products', 'order_detail.products.type_of_products')->get();
        return view('erp.pvd.purchase-planning.verification', compact('orders', 'suppliers', 'purchase_planning', 'purchase_planning_product', 'purchase_planning_detail', 'purchase_planning_verifications', 'supplier_details', 'action'));
    }

    public function check($id)
    {
        if (!Auth::user()->hasPermissionTo('Purchase Planning Check')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }

        $status = new PurchasePlanningVerification();
        $status->purchase_planning_id = $id;
        $status->user_id = Auth::user()->id;
        $status->status = 'checked';
        $status->date = Carbon::now('Asia/Kuala_Lumpur')->format('d-m-Y h:i:s A');
        $status->department_id = Auth::user()->department_id ?? null;
        $status->designation_id = Auth::user()->designation_id ?? null;
        $status->save();
        NotificationController::Notification('Purchase Planning', 'Check', '' . route('purchase_planning.view', $id) . '');

        return redirect()->route('purchase_planning.index')->with('custom_success', 'Purchase Planning has been Successfully Checked!');
    }

    public function verifyHOD($id)
    {
        if (!Auth::user()->hasPermissionTo('Purchase Planning Verify HOD')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }

        $status = new PurchasePlanningVerification();
        $status->purchase_planning_id = $id;
        $status->user_id = Auth::user()->id;
        $status->status = 'verified(hod)';
        $status->date = Carbon::now('Asia/Kuala_Lumpur')->format('d-m-Y h:i:s A');
        $status->department_id = Auth::user()->department_id ?? null;
        $status->designation_id = Auth::user()->designation_id ?? null;
        $status->save();

        NotificationController::Notification('Purchase Planning', 'Verify HOD', '' . route('purchase_planning.view', $id) . '');


        return redirect()->route('purchase_planning.index')->with('custom_success', 'Purchase Planning has been Successfully Verified(HOD)!');
    }

    public function verifyAcc($id)
    {
        if (!Auth::user()->hasPermissionTo('Purchase Planning Verify ACC')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }

        $status = new PurchasePlanningVerification();
        $status->purchase_planning_id = $id;
        $status->user_id = Auth::user()->id;
        $status->status = 'verified(acc)';
        $status->date = Carbon::now('Asia/Kuala_Lumpur')->format('d-m-Y h:i:s A');
        $status->department_id = Auth::user()->department_id ?? null;
        $status->designation_id = Auth::user()->designation_id ?? null;
        $status->save();
        NotificationController::Notification('Purchase Planning', 'Verify ACC', '' . route('purchase_planning.view', $id) . '');

        return redirect()->route('purchase_planning.index')->with('custom_success', 'Purchase Planning has been Successfully Verified(ACC)!');
    }

    public function approve($id)
    {
        if (!Auth::user()->hasPermissionTo('Purchase Planning Approve')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }

        $status = new PurchasePlanningVerification();
        $status->purchase_planning_id = $id;
        $status->user_id = Auth::user()->id;
        $status->status = 'approved';
        $status->date = Carbon::now('Asia/Kuala_Lumpur')->format('d-m-Y h:i:s A');
        $status->department_id = Auth::user()->department_id ?? null;
        $status->designation_id = Auth::user()->designation_id ?? null;
        $status->save();
        NotificationController::Notification('Purchase Planning', 'Approve', '' . route('purchase_planning.view', $id) . '');

        return redirect()->route('purchase_planning.index')->with('custom_success', 'Purchase Planning has been Successfully Approved!');
    }

    public function decline_cancel(Request $request, $id)
    {
        if ($request->decline_cancel == 'decline') {
            if (!Auth::user()->hasPermissionTo('Purchase Planning Decline')) {
                return back()->with('custom_errors', 'You don`t have the right permission');
            }
        } else {
            if (!Auth::user()->hasPermissionTo('Purchase Planning Cancel')) {
                return back()->with('custom_errors', 'You don`t have the right permission');
            }
        }

        $status = new PurchasePlanningVerification();
        $status->purchase_planning_id = $id;
        $status->user_id = Auth::user()->id;
        $status->status = ($request->decline_cancel == 'decline') ? 'declined' : 'cancelled';
        $status->date = Carbon::now('Asia/Kuala_Lumpur')->format('d-m-Y h:i:s A');
        $status->department_id = Auth::user()->department_id ?? null;
        $status->designation_id = Auth::user()->designation_id ?? null;
        $status->reason = $request->decline_cancel_reason ?? null;
        $status->save();

        if ($request->decline_cancel == 'decline') {
            return redirect()->route('purchase_planning.index')->with('custom_success', 'Purchase Planning has been Successfully Declined!');
        }
        return redirect()->route('purchase_planning.index')->with('custom_success', 'Purchase Planning has been Successfully Cancelled!');
    }

    public function destroy(Request $request, $id)
    {
        if (!Auth::user()->hasPermissionTo('Purchase Planning Delete')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $purchase_order = PurchaseOrder::where('pp_id', $id)->first();
        if ($purchase_order) {
            return back()->with('custom_errors', 'Can`t Delete, Purchase Planning is already used in Purchase Order!');
        }
        $purchase_planning = PurchasePlanning::find($id);
        PurchasePlanningProduct::where('purchase_planning_id', $id)->delete();
        PurchasePlanningDetail::where('purchase_planning_id', $id)->delete();
        PurchasePlanningSupplier::where('purchase_planning_id', $id)->delete();
        PurchasePlanningVerification::where('purchase_planning_id', $id)->delete();
        $purchase_planning->delete();
        return redirect()->route('purchase_planning.index')->with('custom_success', 'Purchase Planning has been Deleted Successfully!');
    }
}
