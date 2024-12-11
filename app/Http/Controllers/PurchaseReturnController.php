<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\AreaLocation;
use App\Models\InitailNo;
use App\Models\Supplier;
use App\Models\Location;
use App\Models\PurchaseOrder;
use App\Models\LotNo;
use App\Models\PurchaseReceiveLocation;
use App\Models\PurchaseReturn;
use App\Models\PurchaseReturnProduct;
use App\Models\PurchaseReturnLocation;
use App\Models\Account;
use App\Models\PurchasePrice;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Illuminate\Http\Request;

class PurchaseReturnController extends Controller
{
    public function Data(Request $request)
    {
        if ($request->ajax() && $request->input('columnsData') != null) {
            $columnsData = $request->input('columnsData');
            $draw = $request->input('draw');
            $start = $request->input('start');
            $length = $request->input('length');
            $search = $request->input('search.value');
            $orderByColumnIndex = $request->input('order.0.column'); // Get the index of the column to sort by
            $orderByDirection = $request->input('order.0.dir'); // Get the sort direction ('asc' or 'desc')

            $query = PurchaseReturn::select('id', 'grd_no', 'po_id','supplier_id','qty')->with(['purchase_order','supplier']);


            // Apply search if a search term is provided
            if (!empty($search)) {
                $searchLower = strtolower($search);
                $query->where(function ($q) use ($searchLower) {
                    $q

                        ->where('grd_no', 'like', '%' . $searchLower . '%')
                        ->orWhereHas('purchase_order', function ($query) use ($searchLower) {
                            $query->where('ref_no', 'like', '%' . $searchLower . '%');
                        })
                        ->orWhereHas('supplier', function ($query) use ($searchLower) {
                            $query->where('name', 'like', '%' . $searchLower . '%');
                        })
                        ->orWhere('qty', 'like', '%' . $searchLower . '%');
                });
            }
            $results = null;

            if (!empty($columnsData)) {

                $sortableColumns = [
                    1 => 'grd_no',
                    2 => 'po_id',
                    3 => 'supplier_id',
                    4 => 'qty'


                    // Add more columns as needed
                ];
                if($orderByColumnIndex != null){
                    if($orderByColumnIndex == "0"){
                        $orderByColumn = 'created_at';
                        $orderByDirection = 'ASC';
                    }else{
                        $orderByColumn = $sortableColumns[$orderByColumnIndex];
                    }
                }else{
                    $orderByColumn = 'created_at';
                }
                if($orderByDirection == null){
                    $orderByDirection = 'ASC';
                }
                $results = $query->where(function ($q) use ($columnsData) {
                    foreach ($columnsData as $column) {
                        $searchLower = strtolower($column['value']);

                        switch ($column['index']) {
                            case 1:
                                $q->where('grd_no', 'like', '%' . $searchLower . '%');

                                break;
                            case 2:
                                $q->whereHas('purchase_order', function ($query) use ($searchLower) {
                                    $query->where('ref_no', 'like', '%' . $searchLower . '%');
                                });

                                break;
                                case 3:
                                    $q->whereHas('supplier', function ($query) use ($searchLower) {
                                        $query->where('name', 'like', '%' . $searchLower . '%');
                                    });


                                    break;
                                    case 4:

                                        $q->where('qty', 'like', '%' . $searchLower . '%');
                                        break;
                                        break;


                            default:
                                break;
                        }
                    }
                })->orderBy($orderByColumn, $orderByDirection)->get();

            }

            // type_of_rejection and format the results for DataTables
            $recordsTotal = $results ? $results->count() : 0;

            // Check if there are results before applying skip and take
            if ($results->isNotEmpty()) {
                $uom = $results->skip($start)->take($length)->all();
            } else {
                $uom = [];
            }

            $index = 0;
            foreach ($uom as $row) {
                $row->sr_no = $start + $index + 1;

                // $status = '';

                // $row->date = Carbon::parse($row->date)->format('d/m/Y');
                // dd($row->status);


                $row->action .= '<a class="btn btn-success btn-sm mx-1"
                                        href="' . route('purchase_return.view', $row->id)  .'"><i
                                            class="bi bi-eye"></i></a>
                                    <a class="btn btn-danger btn-sm mx-1"
                                        href="' . route('purchase_return.preview', $row->id)  .'"
                                        target="_blank"><i class="bi bi-file-pdf"></i></a>';
                    $row->action .= '<a class="btn btn-info btn-sm mx-2"
                                            href="' . route('purchase_return.edit', $row->id) .'"><i
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
                                                <form method="POST" action="' . route('purchase_return.destroy', $row->id) . '">
                                                    ' . csrf_field() . '
                                                    ' . method_field('DELETE') . '
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>';

                $index++;
            }

            // // Continue with your response
            $uomsWithoutAction = array_map(function ($row) {
                return $row;
            }, $uom);

            return response()->json([
                'draw' => $draw,
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsTotal, // Total records after filtering
                'data' => array_values($uomsWithoutAction),
            ]);
        } elseif ($request->ajax()) {

            $draw = $request->input('draw');
            $start = $request->input('start');
            $length = $request->input('length');
            $search = $request->input('search.value');
            $orderByColumnIndex = $request->input('order.0.column'); // Get the index of the column to sort by
            $orderByDirection = $request->input('order.0.dir'); // Get the sort direction ('asc' or 'desc')

            $query = PurchaseReturn::select('id', 'grd_no', 'po_id','supplier_id','qty')->with(['purchase_order','supplier']);

            // Apply search if a search term is provided
            if (!empty($search)) {
                $searchLower = strtolower($search);
                $query->where(function ($q) use ($searchLower) {
                    $q

                        ->where('grd_no', 'like', '%' . $searchLower . '%')
                        ->orWhereHas('purchase_order', function ($query) use ($searchLower) {
                            $query->where('ref_no', 'like', '%' . $searchLower . '%');
                        })
                        ->orWhereHas('supplier', function ($query) use ($searchLower) {
                            $query->where('name', 'like', '%' . $searchLower . '%');
                        })
                        ->orWhere('qty', 'like', '%' . $searchLower . '%');
                });
            }

            $sortableColumns = [
                1 => 'grd_no',
                2 => 'po_id',
                3 => 'supplier_id',
                4 => 'qty'


                // Add more columns as needed
            ];
            if($orderByColumnIndex != null){
                if($orderByColumnIndex != "0"){
                    $orderByColumn = $sortableColumns[$orderByColumnIndex];
                    $query->orderBy($orderByColumn, $orderByDirection);
                }else{
                    $query->latest('created_at');
                }
            }else{
                $query->latest('created_at');
            }
            $recordsTotal = $query->count();

            $uom = $query
                ->skip($start)
                ->take($length)
                ->get();

            $uom->each(function ($row, $index)  use (&$start) {
                $row->sr_no = $start + $index + 1;

                $row->action .= '<a class="btn btn-success btn-sm mx-1"
                href="' . route('purchase_return.view', $row->id)  .'"><i
                    class="bi bi-eye"></i></a>
            <a class="btn btn-danger btn-sm mx-1"
                href="' . route('purchase_return.preview', $row->id)  .'"
                target="_blank"><i class="bi bi-file-pdf"></i></a>';

$row->action .= '<a class="btn btn-info btn-sm mx-2"
                    href="' . route('purchase_return.edit', $row->id) .'"><i
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
                                                <form method="POST" action="' . route('purchase_return.destroy', $row->id) . '">
                                                    ' . csrf_field() . '
                                                    ' . method_field('DELETE') . '
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>';

            });

            return response()->json([
                'draw' => $draw,
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsTotal, // Total records after filtering
                'data' => $uom,
            ]);
        }
    }
    public function index(){
        if (
            Auth::user()->hasPermissionTo('Purchase Return List') ||
            Auth::user()->hasPermissionTo('Purchase Return Create') ||
            Auth::user()->hasPermissionTo('Purchase Return Edit') ||
            Auth::user()->hasPermissionTo('Purchase Return View') ||
            Auth::user()->hasPermissionTo('Purchase Return Preview') ||
            Auth::user()->hasPermissionTo('Purchase Return QC') ||
            Auth::user()->hasPermissionTo('Purchase Return Receive') ||
            Auth::user()->hasPermissionTo('Purchase Return Delete')
        ) {
            $purchase_returns = PurchaseReturn::all();
            return view('wms.operations.purchase-return.index', compact('purchase_returns'));
        }
        return back()->with('custom_errors', 'You don`t have Right Permission');
    }

    public function create(){
        if (!Auth::user()->hasPermissionTo('Purchase Return Create')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }
        $ref_no = '';
        $year = Carbon::now('Asia/Kuala_Lumpur')->format('y');
        $setting = InitailNo::where('screen', 'Purchase Return')->first();

        if ($setting) {
            $stock = PurchaseReturn::orderBy('id', 'DESC')->first();
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
            $stock = PurchaseReturn::orderBy('id', 'DESC')->first();
            if ($stock) {
                // Extract running_no from $stock->ref_no which is in format 'SR/running_no/year'
                $parts = explode('/', $stock->ref_no);
                if (count($parts) == 3) {
                    $running_no = (int) $parts[1] + 1;
                } else {
                    $running_no = 1; // Fallback in case the format is unexpected
                }
                $ref_no = 'GRD/' . $running_no . '/' . $year;
            } else {
                $ref_no = 'GRD/1/' . $year;
            }
        }
        $purchase_orders = PurchaseOrder::with(['purchase_order_detail','purchase_order_detail.product','purchase_order_detail.product.type_of_products','purchase_order_detail.product.units'])->get();
        $locations = AreaLocation::select('area_id', 'rack_id', 'level_id')->with('area', 'rack', 'level')->get();
        $suppliers = Supplier::all();
        return view('wms.operations.purchase-return.create', compact('ref_no', 'locations', 'suppliers', 'purchase_orders'));
    }

    public function available_qty(Request $request){
        $qty = Location::select('used_qty')->where('area_id', '=', $request->area_id)->where('rack_id', '=', $request->rack_id)->where('level_id', '=', $request->level_id)->where('product_id', '=', $request->product_id)->where('lot_no', '=', $request->lot_no)->first();
        return $qty;
    }

    public function store(Request $request)
    {
        if (!Auth::user()->hasPermissionTo('Purchase Return Create')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }

        $validated = $request->validate([
            'po_id' => [
                'required',
                Rule::unique('areas', 'name')->whereNull('deleted_at')
            ],
            'date' => [
                'required'
            ],
            'supplier_id' => [
                'required'
            ],
            'for_office' => [
                'required'
            ],
            'products' => [
                'required'
            ]
        ]);


        $inventoryAccount = Account::where('name', 'Inventory')
                                    ->where('type', 'asset')
                                    ->first();
        $accountPayableAccount = Account::where('name', 'Account Payable')
                                        ->where('type', 'liability')
                                        ->first();
        if (!$inventoryAccount || !$accountPayableAccount) {
            return redirect()->back()->with('custom_errors', 'You must create an Inventory or Account Payable account first in Account Details, e.g. Inventory, Account Payable');
        }

        $purchase_return = new PurchaseReturn();
        $purchase_return->po_id = $request->po_id;
        $purchase_return->supplier_id = $request->supplier_id;
        $purchase_return->grd_no = $request->grd_no;
        $purchase_return->for_office = $request->for_office;
        $purchase_return->date = $request->date;
        $purchase_return->remarks = $request->remarks;
        $purchase_return->status = 'in-progress';
        $purchase_return->created_by = Auth::user()->id;
        if($request->file('attachment')){
            $file = $request->file('attachment');
            $filename = date('YmdHis').$file->getClientOriginalName();
            $file->move('order-attachments', $filename);
            $purchase_return->attachment =  $filename;
        }
        $purchase_return->save();

        $total_qty = 0;
        $total_return_amount = 0;

        foreach($request->products as $products){
            $purchase_return_product = new PurchaseReturnProduct();
            $purchase_return_product->purchase_return_id = $purchase_return->id;
            $purchase_return_product->product_id = $products['product_id'] ?? null;
            $purchase_return_product->reason = $products['reason'] ?? null;
            $purchase_return_product->return_qty = $products['returned_qty'] ?? 0;
            $purchase_return_product->save();
            $total_qty += $products['returned_qty'] ?? 0;
            $purchasePrice = PurchasePrice::select('price')->where('product_id', $products['product_id'])->first();
            $product_price = $purchasePrice ? $purchasePrice->price : 0;
            $return_amount = $product_price * ($products['returned_qty'] ?? 0);
            $total_return_amount += $return_amount;
        }

        $purchase_return->update([
            'qty' => $total_qty
        ]);

        Transaction::create([
            'account_id' => $inventoryAccount->id,
            'type' => 'credit',
            'amount' => $total_return_amount,
            'description' => 'Decrease inventory for Purchase Return #' . $purchase_return->po_id,
        ]);

        Transaction::create([
            'account_id' => $accountPayableAccount->id,
            'type' => 'debit',
            'amount' => $total_return_amount,
            'description' => 'Decrease Account Payable Purchase Return #' . $$purchase_return->po_id,
        ]);

        $storedData = json_decode($request->input('details'), true);

        $newArray = collect($storedData)->flatMap(function ($subArray) {
            return $subArray;
        })->sortBy('hiddenId')->values()->toArray();

        foreach ($newArray as $key => $value) {
            $lot_no = LotNo::where('lot_no', $value['lot_no'] ?? null)->first();
            if(!$lot_no){
                $lot_no = new LotNo();
                $lot_no->lot_no = $value['lot_no'] ?? null;
                $lot_no->save();
            }

            $detail = new PurchaseReturnLocation();
            $detail->purchase_return_id = $purchase_return->id;
            $detail->product_id = $value['hiddenId'] ?? null;
            $detail->area_id = $value['area'] ?? null;
            $detail->rack_id = $value['rack'] ?? null;
            $detail->level_id = $value['level'] ?? null;
            $detail->lot_no = $value['lot_no'] ?? null;
            $detail->qty = $value['qty'] ?? 0;
            $detail->save();

            $location = Location::where('area_id', $detail->area_id)->where('rack_id', $detail->rack_id)->where('level_id', $detail->level_id)->where('product_id', $detail->product_id)->where('lot_no', $detail->lot_no)->first();
            if ($location) {
                $location->used_qty += $detail->qty ?? 0;
                $location->save();
            }
        }

        NotificationController::Notification(
            'Purchase Return',
            'Create',
            route('purchase_return.index', $purchase_return->id)
        );

        return redirect()->route('purchase_return.index')->with('custom_success', 'Purchase Return has been Created Successfully!');
    }

    public function edit($id){
        if (!Auth::user()->hasPermissionTo('Purchase Return Edit')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }
        $purchase_return = PurchaseReturn::find($id);
        $purchase_return_products = PurchaseReturnProduct::where('purchase_return_id', $id)->get();
        $purchase_return_locations = PurchaseReturnLocation::where('purchase_return_id', $id)->get();
        $purchase_orders = PurchaseOrder::with(['purchase_order_detail','purchase_order_detail.product','purchase_order_detail.product.type_of_products','purchase_order_detail.product.units'])->get();
        $locations = AreaLocation::select('area_id', 'rack_id', 'level_id')->with('area', 'rack', 'level')->get();
        $suppliers = Supplier::all();
        return view('wms.operations.purchase-return.edit',compact('purchase_return', 'purchase_return_products', 'purchase_return_locations', 'locations', 'suppliers', 'purchase_orders'));
    }

    public function update(Request $request,$id)
    {
        if (!Auth::user()->hasPermissionTo('Purchase Return Edit')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }

        $validated = $request->validate([
            'date' => [
                'required'
            ],
            'supplier_id' => [
                'required'
            ],
            'for_office' => [
                'required'
            ],
            'products' => [
                'required'
            ]
        ]);

        $purchase_return = PurchaseReturn::find($id);
        $purchase_return->supplier_id = $request->supplier_id;
        $purchase_return->for_office = $request->for_office;
        $purchase_return->date = $request->date;
        $purchase_return->remarks = $request->remarks;
        $purchase_return->status = 'in-progress';
        $purchase_return->created_by = Auth::user()->id;
        if($request->file('attachment')){
            $file = $request->file('attachment');
            $filename = date('YmdHis').$file->getClientOriginalName();
            $file->move('order-attachments', $filename);
            $purchase_return->attachment =  $filename;
        }
        $purchase_return->save();

        $details = PurchaseReturnProduct::where('purchase_return_id', '=', $id)->get();
        $detailIds = $details->pluck('product_id')->toArray();
        $existingDetails = PurchaseReturnLocation::whereIn('product_id', $detailIds)->get();

        foreach ($existingDetails as $existingDetail) {
            if($existingDetail->area_id != null && $existingDetail->rack_id != null && $existingDetail->level_id != null){
                $location = Location::where('area_id', $existingDetail->area_id)->where('rack_id', $existingDetail->rack_id)->where('level_id', $existingDetail->level_id)->where('product_id', $existingDetail->product_id)->where('lot_no', $existingDetail->lot_no)->first();

                if ($location) {
                    $location->used_qty -= $existingDetail->qty ?? 0;
                    $location->save();
                }
            }
        }

        $total_qty = 0;
        PurchaseReturnProduct::where('purchase_return_id', $id)->delete();
        foreach($request->products as $products){
            $purchase_return_product = new PurchaseReturnProduct();
            $purchase_return_product->purchase_return_id = $purchase_return->id;
            $purchase_return_product->product_id = $products['product_id'] ?? null;
            $purchase_return_product->reason = $products['reason'] ?? null;
            $purchase_return_product->return_qty = $products['returned_qty'] ?? 0;
            $purchase_return_product->save();
            $total_qty += $products['returned_qty'] ?? 0;
        }

        $purchase_return->update([
            'qty' => $total_qty
        ]);

        $storedData = json_decode($request->input('details'), true);

        $newArray = collect($storedData)->flatMap(function ($subArray) {
            return $subArray;
        })->sortBy('hiddenId')->values()->toArray();

        PurchaseReturnLocation::where('purchase_return_id', $id)->delete();
        foreach ($newArray as $key => $value) {
            $lot_no = LotNo::where('lot_no', $value['lot_no'] ?? null)->first();
            if(!$lot_no){
                $lot_no = new LotNo();
                $lot_no->lot_no = $value['lot_no'] ?? null;
                $lot_no->save();
            }

            $detail = new PurchaseReturnLocation();
            $detail->purchase_return_id = $purchase_return->id;
            $detail->product_id = $value['hiddenId'] ?? null;
            $detail->area_id = $value['area'] ?? null;
            $detail->rack_id = $value['rack'] ?? null;
            $detail->level_id = $value['level'] ?? null;
            $detail->lot_no = $value['lot_no'] ?? null;
            $detail->qty = $value['qty'] ?? 0;
            $detail->save();

            $location = Location::where('area_id', $detail->area_id)->where('rack_id', $detail->rack_id)->where('level_id', $detail->level_id)->where('product_id', $detail->product_id)->where('lot_no', $detail->lot_no)->first();
            if ($location) {
                $location->used_qty += $detail->qty ?? 0;
                $location->save();
            }
        }

        return redirect()->route('purchase_return.index')->with('custom_success', 'Purchase Return has been Updated Successfully!');
    }

    public function qc($id){
        if (!Auth::user()->hasPermissionTo('Purchase Return QC')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }
        $suppliers = Supplier::all();
        $purchase_return = PurchaseReturn::find($id);
        $purchase_return_products = PurchaseReturnProduct::where('purchase_return_id', $id)->get();
        $purchase_return_locations = PurchaseReturnLocation::where('purchase_return_id', $id)->get();
        $locations = AreaLocation::select('area_id', 'rack_id', 'level_id')->with('area', 'rack', 'level')->get();
        return view('wms.operations.purchase-return.qc',compact('purchase_return', 'purchase_return_products', 'purchase_return_locations', 'locations', 'suppliers'));
    }

    public function qc_update(Request $request, $id){
        if (!Auth::user()->hasPermissionTo('Purchase Return QC')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }

        $validated = $request->validate([
            'products' => [
                'required'
            ]
        ]);

        $purchase_return = PurchaseReturn::find($id);
        $purchase_return->status = 'checked';
        $purchase_return->checked_by = Auth::user()->id;
        $purchase_return->checked_by_time = Carbon::now('Asia/Kuala_Lumpur')->format('d-m-Y H:i:s');
        $purchase_return->save();

        foreach($request->products as $products){
            $purchase_return_qc = PurchaseReturnProduct::where('purchase_return_id', $id)->where('product_id', $products['product_id'])->first();
            $purchase_return_qc->purchase_return_id = $id;
            $purchase_return_qc->product_id = $products['product_id'] ?? null;
            $purchase_return_qc->reject_qty = $products['rejected_qty'] ?? 0;
            $purchase_return_qc->reject_remarks = $products['rejected_remarks'] ?? null;
            $purchase_return_qc->save();
        }

        NotificationController::Notification(
            'Purchase Return',
            'QC',
            route('purchase_return.index', $purchase_return->id)
        );

        return redirect()->route('purchase_return.index')->with('custom_success', 'Purchase Return QC has been Saved!');
    }

    public function receive($id){
        if (!Auth::user()->hasPermissionTo('Purchase Return Receive')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }
        $suppliers = Supplier::all();
        $purchase_return = PurchaseReturn::find($id);
        $purchase_return_products = PurchaseReturnProduct::where('purchase_return_id', $id)->get();
        $locations = AreaLocation::select('area_id', 'rack_id', 'level_id')->with('area', 'rack', 'level')->get();
        return view('wms.operations.purchase-return.receive',compact('purchase_return', 'purchase_return_products', 'locations', 'suppliers'));
    }

    public function receive_update(Request $request, $id){
        if (!Auth::user()->hasPermissionTo('Purchase Return Receive')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }

        $validated = $request->validate([
            'products' => [
                'required'
            ]
        ]);

        $purchase_return = PurchaseReturn::find($id);
        $purchase_return->status = 'completed';
        $purchase_return->received_by = Auth::user()->id;
        $purchase_return->received_by_time = Carbon::now('Asia/Kuala_Lumpur')->format('d-m-Y H:i:s');
        $purchase_return->save();

        foreach($request->products as $products){
            $purchase_return_qc = PurchaseReturnProduct::where('purchase_return_id', $id)->where('product_id', $products['product_id'])->first();
            $purchase_return_qc->to_receive = $products['to_receive'] ?? 0;
            $purchase_return_qc->receive_qty = $products['receive_qty'] ?? 0;
            $purchase_return_qc->receive_remarks = $products['receive_remarks'] ?? null;
            $purchase_return_qc->save();
        }

        $storedData = json_decode($request->input('details'), true);

        $newArray = collect($storedData)->flatMap(function ($subArray) {
            return $subArray;
        })->sortBy('hiddenId')->values()->toArray();

        foreach ($newArray as $key => $value) {
            $lot_no = new LotNo();
            $lot_no->lot_no = $value['lot_no'] ?? null;
            $lot_no->save();

            $detail = new PurchaseReceiveLocation();
            $detail->purchase_return_id = $purchase_return->id;
            $detail->product_id = $value['hiddenId'] ?? null;
            $detail->area_id = $value['area'] ?? null;
            $detail->rack_id = $value['rack'] ?? null;
            $detail->level_id = $value['level'] ?? null;
            $detail->lot_no = $value['lot_no'] ?? null;
            $detail->qty = $value['qty'] ?? 0;
            $detail->save();

            $location = Location::where('area_id', $detail->area_id)->where('rack_id', $detail->rack_id)->where('level_id', $detail->level_id)->where('product_id', $detail->product_id)->where('lot_no', $detail->lot_no)->first();
            if ($location) {
                $location->used_qty += $detail->qty ?? 0;
                $location->save();
            }
        }

        NotificationController::Notification(
            'Purchase Return',
            'Receive',
            route('purchase_return.index', $purchase_return->id)
        );

        return redirect()->route('purchase_return.index')->with('custom_success', 'Purchase Return has been Successfully Received!');
    }

    public function view($id){
        if (!Auth::user()->hasPermissionTo('Purchase Return View')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }
        $suppliers = Supplier::all();
        $purchase_return = PurchaseReturn::find($id);
        $purchase_return_products = PurchaseReturnProduct::where('purchase_return_id', $id)->get();
        $purchase_return_locations = PurchaseReturnLocation::where('purchase_return_id', $id)->get();
        $locations = AreaLocation::select('area_id', 'rack_id', 'level_id')->with('area', 'rack', 'level')->get();
        return view('wms.operations.purchase-return.view',compact('purchase_return', 'purchase_return_products', 'purchase_return_locations', 'locations', 'suppliers'));
    }

    public function preview(Request $request, $id){
        if (!Auth::user()->hasPermissionTo('Purchase Order Preview')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $purchase_return = PurchaseReturn::find($id);
        $purchase_return_products = PurchaseReturnProduct::where('purchase_return_id', $id)->get();

        $pdf = FacadePdf::loadView('wms.operations.purchase-return.preview', compact('purchase_return', 'purchase_return_products'))->setPaper('a4');
        return $pdf->stream('purchase-return.preview');
    }

    public function destroy($id){
        if (!Auth::user()->hasPermissionTo('Purchase Return Delete')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }
        $purchase_return = PurchaseReturn::find($id);

        $details = PurchaseReturnProduct::where('purchase_return_id', '=', $id)->get();
        $detailIds = $details->pluck('product_id')->toArray();
        $existingDetails = PurchaseReturnLocation::whereIn('product_id', $detailIds)->get();

        foreach ($existingDetails as $existingDetail) {
            if($existingDetail->area_id != null && $existingDetail->rack_id != null && $existingDetail->level_id != null){
                $location = Location::where('area_id', $existingDetail->area_id)->where('rack_id', $existingDetail->rack_id)->where('level_id', $existingDetail->level_id)->where('product_id', $existingDetail->product_id)->where('lot_no', $existingDetail->lot_no)->first();

                if ($location) {
                    $location->used_qty -= $existingDetail->qty ?? 0;
                    $location->save();
                }
            }
        }

        PurchaseReturnProduct::where('purchase_return_id', $id)->delete();
        PurchaseReturnLocation::where('purchase_return_id', $id)->delete();
        $purchase_return->delete();
        return redirect()->route('purchase_return.index')->with('custom_success', 'Purchase Return has been Successfully Deleted!');
    }
}
