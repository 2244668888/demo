<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\AreaLocation;
use App\Models\InitailNo;
use App\Models\LotNo;
use App\Models\Location;
use App\Models\TypeOfRejection;
use App\Models\GoodReceiving;
use App\Models\GoodReceivingProduct;
use App\Models\GoodReceivingQc;
use App\Models\GoodReceivingLocation;
use App\Models\Product;
use App\Models\PurchaseReturn;
use App\Models\PurchaseOrder;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class GoodReceivingController extends Controller
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

            $query = GoodReceiving::select('id', 'po_id', 'received_qty', 'incoming_qty', 'status', 'date', 'created_by')->with(['purchase_order', 'purchase_return', 'user']);

            // Apply search if a search term is provided
            if (!empty($search)) {
                $searchLower = strtolower($search);
                $query->where(function ($q) use ($searchLower) {
                    $q

                        ->whereHas('purchase_order', function ($query) use ($searchLower) {
                            $query->where('ref_no', 'like', '%' . $searchLower . '%');
                        })
                        ->orWhereHas('purchase_return', function ($query) use ($searchLower) {
                            $query->where('grd_no', 'like', '%' . $searchLower . '%');
                        })
                        ->orWhereHas('purchase_order.supplier', function ($query) use ($searchLower) {
                            $query->where('name', 'like', '%' . $searchLower . '%');
                        })
                        ->orWhereHas('purchase_return.supplier', function ($query) use ($searchLower) {
                            $query->where('name', 'like', '%' . $searchLower . '%');
                        })
                        ->orWhere('incoming_qty', 'like', '%' . $searchLower . '%')
                        ->orWhere('received_qty', 'like', '%' . $searchLower . '%')
                        ->orWhere('date', 'like', '%' . $searchLower . '%')
                        ->orWhereHas('user', function ($query) use ($searchLower) {
                            $query->where('user_name', 'like', '%' . $searchLower . '%');
                        })
                        ->orWhere('status', 'like', '%' . $searchLower . '%');
                });
            }
            $results = null;

            if (!empty($columnsData)) {

                $sortableColumns = [
                    1 => 'po_id',
                    2 => 'po_id',
                    3 => 'incoming_qty',
                    4 => 'received_qty',
                    5 => 'date',
                    6 => 'created_by',
                    7 => 'status',

                    // Add more columns as needed
                ];
                if ($orderByColumnIndex != null) {
                    if ($orderByColumnIndex == "0") {
                        $orderByColumn = 'created_at';
                        $orderByDirection = 'ASC';
                    } else {
                        $orderByColumn = $sortableColumns[$orderByColumnIndex];
                    }
                } else {
                    $orderByColumn = 'created_at';
                }
                if ($orderByDirection == null) {
                    $orderByDirection = 'ASC';
                }
                $results = $query->where(function ($q) use ($columnsData) {
                    foreach ($columnsData as $column) {
                        $searchLower = strtolower($column['value']);

                        switch ($column['index']) {
                            case 1:
                                $q->whereHas('purchase_order', function ($query) use ($searchLower) {
                                    $query->where('ref_no', 'like', '%' . $searchLower . '%');
                                });

                                $q->whereHas('purchase_return', function ($query) use ($searchLower) {
                                    $query->where('grd_no', 'like', '%' . $searchLower . '%');
                                });



                                break;
                            case 2:
                                $q->whereHas('purchase_order.supplier', function ($query) use ($searchLower) {
                                    $query->where('name', 'like', '%' . $searchLower . '%');
                                });

                                $q->whereHas('purchase_return.supplier', function ($query) use ($searchLower) {
                                    $query->where('name', 'like', '%' . $searchLower . '%');
                                });

                                break;
                            case 3:
                                $q->where('incoming_qty', 'like', '%' . $searchLower . '%');
                                break;
                            case 4:
                                $q->where('received_qty', 'like', '%' . $searchLower . '%');
                                break;

                            case 5:
                                $q->where('date', 'like', '%' . $searchLower . '%');
                                break;
                            case 6:
                                $q->whereHas('user', function ($query) use ($searchLower) {
                                    $query->where('user_name', 'like', '%' . $searchLower . '%');
                                });


                                break;
                            case 7:
                                $q->where('status', 'like', '%' . $searchLower . '%');
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

                $row->date = Carbon::parse($row->date)->format('d/m/Y');
                $row->po_number = $row->po_id != null ? $row->purchase_order->ref_no : $row->purchase_return->grd_no ?? '';
                $row->name_supplier = $row->po_id != null ? $row->purchase_order->supplier->name ?? '' : $row->purchase_return->supplier->name ?? '';
                // $row->created_by = $row->user->user_name;

                // dd($row->status);


                $row->action .= '<div class="d-flex"><a class="btn btn-success btn-sm me-1"
                                        href="' . route('good_receiving.view', $row->id) . '"><i title="View"
                                            class="bi bi-eye"></i></a>';
                // $row->status = $status;
                if ($row->status == 'received') {
                    $row->action .= '  <a class="btn btn-info btn-sm me-1"
                        href="' . route('good_receiving.edit', $row->id) . '"><i
                            title="Edit" class="bi bi-pencil"></i></a>
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
                                                <form method="POST" action="' . route('good_receiving.destroy', $row->id) . '">
                                                    ' . csrf_field() . '
                                                    ' . method_field('DELETE') . '
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                    <a class="btn btn-warning btn-sm me-1"
                        href="' . route('good_receiving.qc', $row->id) . '"><i title="QC"
                            class="bi bi-clipboard-check"></i></a>';
                } elseif ($row->status == "checked") {
                    $row->action .= '<a class="btn btn-success btn-sm me-1"
                        href="' . route('good_receiving.allocation', $row->id) . '"
                        title="Allocation"><i class="bi bi-sliders"></i></a></div>';
                }

                if ($row->status == 'received') {
                    $row->status = '<span class="badge border border-primary text-primary">Received</span>';
                } elseif ($row->status == 'checked') {
                    $row->status = '<span class="badge border border-warning text-warning">Checked</span>';
                } elseif ($row->status == 'completed') {
                    $row->status = '<span class="badge border border-success text-success">Completed</span>';
                }
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

            $query = GoodReceiving::select('id', 'po_id', 'received_qty', 'incoming_qty', 'status', 'date', 'created_by')->with(['purchase_order', 'purchase_return']);
            // dd($query->get());
            // Apply search if a search term is provided
            if (!empty($search)) {
                $searchLower = strtolower($search);
                $query->where(function ($q) use ($searchLower) {
                    $q

                        ->whereHas('purchase_order', function ($query) use ($searchLower) {
                            $query->where('ref_no', 'like', '%' . $searchLower . '%');
                        })
                        ->orWhereHas('purchase_return', function ($query) use ($searchLower) {
                            $query->where('grd_no', 'like', '%' . $searchLower . '%');
                        })
                        ->orWhereHas('purchase_order.supplier', function ($query) use ($searchLower) {
                            $query->where('name', 'like', '%' . $searchLower . '%');
                        })
                        ->orWhereHas('purchase_return.supplier', function ($query) use ($searchLower) {
                            $query->where('name', 'like', '%' . $searchLower . '%');
                        })
                        ->orWhere('incoming_qty', 'like', '%' . $searchLower . '%')
                        ->orWhere('received_qty', 'like', '%' . $searchLower . '%')
                        ->orWhere('date', 'like', '%' . $searchLower . '%')
                        ->orWhereHas('user', function ($query) use ($searchLower) {
                            $query->where('user_name', 'like', '%' . $searchLower . '%');
                        })
                        ->orWhere('status', 'like', '%' . $searchLower . '%');
                });
            }
            $sortableColumns = [
                1 => 'po_id',
                2 => 'po_id',
                3 => 'incoming_qty',
                4 => 'received_qty',
                5 => 'date',
                6 => 'created_by',
                7 => 'status',

                // Add more columns as needed
            ];
            if ($orderByColumnIndex != null) {
                if ($orderByColumnIndex != "0") {
                    $orderByColumn = $sortableColumns[$orderByColumnIndex];
                    $query->orderBy($orderByColumn, $orderByDirection);
                } else {
                    $query->latest('created_at');
                }
            } else {
                $query->latest('created_at');
            }
            $recordsTotal = $query->count();

            $uom = $query
                ->skip($start)
                ->take($length)
                ->get();

            $uom->each(function ($row, $index)  use (&$start) {
                $row->sr_no = $start + $index + 1;
                $row->date = Carbon::parse($row->date)->format('d/m/Y');
                $row->po_number = $row->po_id != null ? $row->purchase_order->ref_no : $row->purchase_return->grd_no ?? '';
                $row->name_supplier = $row->po_id != null ? $row->purchase_order->supplier->name ?? '' : $row->purchase_return->supplier->name ?? '';
                $row->created_by = $row->user->user_name;
                // dd($row->po_number);
                // $row->date = Carbon::parse($row->date)->format('d/m/Y');


                $row->action .= '<div class="d-flex"><a class="btn btn-success btn-sm me-1"
                                        href="' . route('good_receiving.view', $row->id) . '"><i title="View"
                                            class="bi bi-eye"></i></a>';
                if ($row->status == 'received') {
                    $row->action .= '  <a class="btn btn-info btn-sm me-1"
                        href="' . route('good_receiving.edit', $row->id) . '"><i
                            title="Edit" class="bi bi-pencil"></i></a>
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
                                                <form method="POST" action="' . route('good_receiving.destroy', $row->id) . '">
                                                    ' . csrf_field() . '
                                                    ' . method_field('DELETE') . '
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                    <a class="btn btn-warning btn-sm me-1"
                        href="' . route('good_receiving.qc', $row->id) . '"><i title="QC"
                            class="bi bi-clipboard-check"></i></a>';
                } elseif ($row->status == "checked") {
                    $row->action .= '<a class="btn btn-success btn-sm me-1"
                        href="' . route('good_receiving.allocation', $row->id) . '"
                        title="Allocation"><i class="bi bi-sliders"></i></a></div>';
                }
                if ($row->status == 'received') {
                    $row->status = '<span class="badge border border-primary text-primary">Received</span>';
                } elseif ($row->status == 'checked') {
                    $row->status = '<span class="badge border border-warning text-warning">Checked</span>';
                } elseif ($row->status == 'completed') {
                    $row->status = '<span class="badge border border-success text-success">Completed</span>';
                }
            });

            return response()->json([
                'draw' => $draw,
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsTotal, // Total records after filtering
                'data' => $uom,
            ]);
        }
    }
    public function index()
    {
        if (
            Auth::user()->hasPermissionTo('Good Receiving List') ||
            Auth::user()->hasPermissionTo('Good Receiving Create') ||
            Auth::user()->hasPermissionTo('Good Receiving Edit') ||
            Auth::user()->hasPermissionTo('Good Receiving Receive') ||
            Auth::user()->hasPermissionTo('Good Receiving QC') ||
            Auth::user()->hasPermissionTo('Good Receiving Approve') ||
            Auth::user()->hasPermissionTo('Good Receiving Allocation') ||
            Auth::user()->hasPermissionTo('Good Receiving View') ||
            Auth::user()->hasPermissionTo('Good Receiving Delete')
        ) {
            $good_receivings = GoodReceiving::all();
            return view('wms.operations.good-receiving.index', compact('good_receivings'));
        }
        return back()->with('custom_errors', 'You don`t have Right Permission');
    }

    public function create()
    {
        if (!Auth::user()->hasPermissionTo('Good Receiving Create')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }
        $ref_no = '';
        $year = Carbon::now('Asia/Kuala_Lumpur')->format('y');
        $setting = InitailNo::where('screen', 'Good Receiving')->first();

        if ($setting) {
            $stock = GoodReceiving::orderBy('id', 'DESC')->first();
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
            $stock = GoodReceiving::orderBy('id', 'DESC')->first();
            if ($stock) {
                // Extract running_no from $stock->ref_no which is in format 'SR/running_no/year'
                $parts = explode('/', $stock->ref_no);
                if (count($parts) == 3) {
                    $running_no = (int) $parts[1] + 1;
                } else {
                    $running_no = 1; // Fallback in case the format is unexpected
                }
                $ref_no = 'GR/' . $running_no . '/' . $year;
            } else {
                $ref_no = 'GR/1/' . $year;
            }
        }
        $products = Product::with('units')->get();
        $suppliers = Supplier::select('id', 'name')->get();
        $purchase_returns = PurchaseReturn::with('purchase_return_detail.product.units', 'supplier')->get();
        $purchase_orders = PurchaseOrder::with('purchase_order_detail.product.units', 'supplier')->get();
        return view('wms.operations.good-receiving.create', compact('ref_no', 'purchase_orders', 'purchase_returns', 'suppliers', 'products'));
    }

    public function store(Request $request)
    {
        if (!Auth::user()->hasPermissionTo('Good Receiving Create')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }

        $validated = $request->validate([
            'pr_id' => [
                'required_if:po_pr,1',
                Rule::unique('good_receivings', 'pr_id')->whereNull('deleted_at')
            ],
            'po_id' => [
                'required_unless:po_pr,1',
                Rule::unique('good_receivings', 'po_id')->whereNull('deleted_at')
            ],
            'date' => [
                'required'
            ],
            'products' => [
                'required'
            ]
        ]);

        $good_receiving = new GoodReceiving();
        $good_receiving->po_pr = $request->po_pr;
        if ($request->po_pr == 1) {
            $good_receiving->pr_id = $request->pr_id;
        } else {
            $good_receiving->po_id = $request->po_id;
            $good_receiving->supplier_id = $request->order_supplier;
        }
        if ($request->file('attachment')) {
            $file = $request->file('attachment');
            $filename = date('YmdHis') . $file->getClientOriginalName();
            $file->move('order-attachments', $filename);
            $good_receiving->attachment =  $filename;
        }
        $good_receiving->ref_no = $request->ref_no;
        $good_receiving->date = $request->date;
        $good_receiving->created_by = Auth::user()->id;
        $good_receiving->status = 'received';
        $good_receiving->save();

        $total_qty = 0;
        $incoming_qty = 0;
        foreach ($request->products as $products) {
            $good_receiving_product = new GoodReceivingProduct();
            $good_receiving_product->gr_id = $good_receiving->id;
            $good_receiving_product->product_id = $products['product_id'] ?? null;
            $good_receiving_product->date = $products['date'] ?? null;
            $good_receiving_product->incoming_qty = $products['qty'] ?? 0;
            $good_receiving_product->received_qty = $products['received_qty'] ?? 0;
            $good_receiving_product->remarks = $products['remarks'] ?? null;
            $good_receiving_product->save();
            $total_qty += $products['received_qty'] ?? 0;
            $incoming_qty += $products['qty'] ?? 0;
        }

        $good_receiving->update([
            'received_qty' => $total_qty,
            'incoming_qty' => $incoming_qty
        ]);

        NotificationController::Notification(
            'Good Receiving',
            'Create',
            route('good_receiving.view', $good_receiving->id)
        );

        return redirect()->route('good_receiving.index')->with('custom_success', 'Good Receiving has been Created Successfully !');
    }


    public function edit($id)
    {
        if (!Auth::user()->hasPermissionTo('Good Receiving Edit')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }
        $good_receiving = GoodReceiving::find($id);
        $products = Product::with('units')->get();
        $suppliers = Supplier::select('id', 'name')->get();
        $good_receiving_products = GoodReceivingProduct::where('gr_id', $id)->get();
        $purchase_returns = PurchaseReturn::with('purchase_return_detail.product.units', 'supplier')->get();
        $purchase_orders = PurchaseOrder::with('purchase_order_detail.product.units', 'supplier')->get();
        return view('wms.operations.good-receiving.edit', compact('good_receiving', 'good_receiving_products', 'purchase_orders', 'purchase_returns', 'suppliers', 'products'));
    }

    public function update(Request $request, $id)
    {
        if (!Auth::user()->hasPermissionTo('Good Receiving Edit')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }

        $validated = $request->validate([
            'pr_id' => [
                'required_if:po_pr,1',
                Rule::unique('outgoings', 'pr_id')->whereNull('deleted_at')
            ],
            'po_id' => [
                'required_unless:po_pr,1',
                Rule::unique('outgoings', 'pr_id')->whereNull('deleted_at')
            ],
            'date' => [
                'required'
            ],
            'products' => [
                'required'
            ]
        ]);

        $good_receiving = GoodReceiving::find($id);
        $good_receiving->po_pr = $request->po_pr;
        if ($request->po_pr == 1) {
            $good_receiving->pr_id = $request->pr_id;
        } else {
            $good_receiving->po_id = $request->po_id;
            $good_receiving->supplier_id = $request->order_supplier;
        }
        if ($request->file('attachment')) {
            $file = $request->file('attachment');
            $filename = date('YmdHis') . $file->getClientOriginalName();
            $file->move('order-attachments', $filename);
            $good_receiving->attachment =  $filename;
        }
        $good_receiving->date = $request->date;
        $good_receiving->created_by = Auth::user()->id;
        $good_receiving->status = 'received';
        $good_receiving->save();

        $total_qty = 0;
        $incoming_qty = 0;
        GoodReceivingProduct::where('gr_id', $id)->delete();
        foreach ($request->products as $products) {
            $good_receiving_product = new GoodReceivingProduct();
            $good_receiving_product->gr_id = $good_receiving->id;
            $good_receiving_product->product_id = $products['product_id'] ?? null;
            $good_receiving_product->date = $products['date'] ?? null;
            $good_receiving_product->incoming_qty = $products['qty'] ?? 0;
            $good_receiving_product->received_qty = $products['received_qty'] ?? 0;
            $good_receiving_product->remarks = $products['remarks'] ?? null;
            $good_receiving_product->save();
            $total_qty += $products['received_qty'] ?? 0;
            $incoming_qty += $products['qty'] ?? 0;
        }

        $good_receiving->update([
            'received_qty' => $total_qty,
            'incoming_qty' => $incoming_qty
        ]);

        return redirect()->route('good_receiving.index')->with('custom_success', 'Good Receiving has been Updated Successfully !');
    }

    public function receive($id)
    {
        if (!Auth::user()->hasPermissionTo('Good Receiving Receive')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }
        $good_receiving = GoodReceiving::find($id);
        $good_receiving->status = 'received';
        $good_receiving->save();
        return redirect()->route('good_receiving.index')->with('custom_success', 'Good Receiving has been Successfully Received!');
    }

    public function qc($id)
    {
        if (!Auth::user()->hasPermissionTo('Good Receiving QC')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }
        $good_receiving = GoodReceiving::find($id);
        $suppliers = Supplier::select('id', 'name')->get();
        $good_receiving_products = GoodReceivingProduct::where('gr_id', $id)->get();
        $good_receiving_qcs = GoodReceivingQc::where('gr_id', $id)->get();
        $type_of_rejections = TypeOfRejection::select('id', 'type')->get();


        return view('wms.operations.good-receiving.qc', compact('good_receiving', 'good_receiving_products', 'type_of_rejections', 'good_receiving_qcs', 'suppliers'));
    }

    public function qc_update(Request $request, $id)
    {
        if (!Auth::user()->hasPermissionTo('Good Receiving QC')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }
        $validated = $request->validate([
            'products' => [
                'required'
            ]
        ]);

        $good_receiving = GoodReceiving::find($id);

        $anyStatusZero = false;
        foreach ($request->products as $products) {
            $good_receiving_product = GoodReceivingProduct::where('gr_id', $id)->where('product_id', $products['product_id'])->first();
            $good_receiving_product->rejected_qty = $products['rejected_qty'] ?? 0;
            $good_receiving_product->accepted_qty = $good_receiving_product->received_qty - $products['rejected_qty'] ?? 0;
            $good_receiving_product->status = $products['status'] ?? 0;
            $good_receiving_product->save();

            if ($good_receiving_product->status == 0) {
                $anyStatusZero = true;
            }
        }

        if (!$anyStatusZero) {
            $good_receiving->status = 'checked';
            $good_receiving->save();
        }

        $storedData = json_decode($request->input('details'), true);

        $newArray = collect($storedData)->flatMap(function ($subArray) {
            return $subArray;
        })->sortBy('hiddenId')->values()->toArray();

        GoodReceivingQc::where('gr_id', $id)->delete();

        foreach ($newArray as $key => $value) {
            $detail = new GoodReceivingQc();
            $detail->gr_id = $good_receiving->id;
            $detail->product_id = $value['hiddenId'] ?? null;
            $detail->rt_id = $value['rejection'] ?? null;
            $detail->comment = $value['comments'] ?? null;
            $detail->qty = $value['qty'] ?? 0;
            $detail->save();

        }
        NotificationController::Notification(
            'Good Receiving',
            'QC',
            route('good_receiving.view', $good_receiving->id)
        );
        return redirect()->route('good_receiving.index')->with('custom_success', 'Good Receiving has been Successfully Checked!');
    }

    public function approve($id)
    {
        if (!Auth::user()->hasPermissionTo('Good Receiving Approve')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }
        $good_receiving = GoodReceiving::find($id);
        $good_receiving->status = 'approved';
        $good_receiving->save();
        return redirect()->route('good_receiving.index')->with('custom_success', 'Good Receiving has been Successfully Approved!');
    }

    public function allocation($id)
    {
        if (!Auth::user()->hasPermissionTo('Good Receiving Allocation')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }
        $good_receiving = GoodReceiving::find($id);
        $suppliers = Supplier::select('id', 'name')->get();
        $good_receiving_products = GoodReceivingProduct::where('gr_id', $id)->where('status', '=', 1)->get();
        $locations = AreaLocation::select('area_id', 'rack_id', 'level_id')->with('area', 'rack', 'level')->get();
        return view('wms.operations.good-receiving.allocation', compact('good_receiving', 'good_receiving_products', 'locations', 'suppliers'));
    }

    public function allocation_update(Request $request, $id)
    {
        if (!Auth::user()->hasPermissionTo('Good Receiving Allocation')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }
        $validated = $request->validate([
            'products' => [
                'required'
            ]
        ]);

        $good_receiving = GoodReceiving::find($id);
        $good_receiving->status = 'completed';
        $good_receiving->save();

        foreach ($request->products as $products) {
            $good_receiving_product = GoodReceivingProduct::where('gr_id', $id)->where('product_id', $products['product_id'])->first();
            $good_receiving_product->allocation_remarks = $products['allocation_remarks'] ?? 0;
            $good_receiving_product->save();
        }

        $storedData = json_decode($request->input('details'), true);

        $newArray = collect($storedData)->flatMap(function ($subArray) {
            return $subArray;
        })->sortBy('hiddenId')->values()->toArray();

        foreach ($newArray as $key => $value) {
            $lot_no = new LotNo();
            $lot_no->lot_no = $value['lot_no'] ?? null;
            $lot_no->save();

            $detail = new GoodReceivingLocation();
            $detail->gr_id = $good_receiving->id;
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
            } else {
                if ($detail->area_id != null && $detail->rack_id != null && $detail->level_id != null) {
                    $location = new Location();
                    $location->area_id = $detail->area_id;
                    $location->rack_id = $detail->rack_id;
                    $location->level_id = $detail->level_id;
                    $location->product_id = $detail->product_id;
                    $location->lot_no = $detail->lot_no;
                    $location->used_qty = $detail->qty ?? 0;
                }
            }
            if ($detail->area_id != null && $detail->rack_id != null && $detail->level_id != null) {
                $location->save();
            }
        }

        return redirect()->route('good_receiving.index')->with('custom_success', 'Good Receiving has been Successfully Placed!');
    }

    public function view($id)
    {
        if (!Auth::user()->hasPermissionTo('Good Receiving View')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }
        $good_receiving = GoodReceiving::find($id);
        $suppliers = Supplier::select('id', 'name')->get();
        $good_receiving_qcs = GoodReceivingQc::where('gr_id', $id)->get();
        $type_of_rejections = TypeOfRejection::select('id', 'type')->get();
        $good_receiving_products = GoodReceivingProduct::where('gr_id', $id)->get();
        $good_receiving_locations = GoodReceivingLocation::where('gr_id', $id)->get();
        $locations = AreaLocation::select('area_id', 'rack_id', 'level_id')->with('area', 'rack', 'level')->get();
        return view('wms.operations.good-receiving.view', compact('good_receiving', 'good_receiving_qcs', 'good_receiving_products', 'good_receiving_locations', 'locations', 'type_of_rejections', 'suppliers'));
    }

    public function destroy($id)
    {
        if (!Auth::user()->hasPermissionTo('Good Receiving Delete')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }
        $good_receiving = GoodReceiving::find($id);

        $details = GoodReceivingProduct::where('gr_id', '=', $id)->get();
        $detailIds = $details->pluck('product_id')->toArray();
        $existingDetails = GoodReceivingLocation::whereIn('product_id', $detailIds)->get();

        foreach ($existingDetails as $existingDetail) {
            if ($existingDetail->area_id != null && $existingDetail->rack_id != null && $existingDetail->level_id != null) {
                $location = Location::where('area_id', $existingDetail->area_id)->where('rack_id', $existingDetail->rack_id)->where('level_id', $existingDetail->level_id)->where('product_id', $existingDetail->product_id)->where('lot_no', $existingDetail->lot_no)->first();

                if ($location) {
                    $location->used_qty -= $existingDetail->qty ?? 0;
                    $location->save();
                }
            }
        }

        GoodReceivingQc::where('gr_id', $id)->delete();
        GoodReceivingProduct::where('gr_id', $id)->delete();
        GoodReceivingLocation::where('gr_id', $id)->delete();
        $good_receiving->delete();
        return redirect()->route('good_receiving.index')->with('custom_success', 'Good Receiving has been Successfully Deleted!');
    }
}
