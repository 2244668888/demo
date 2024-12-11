<?php

namespace App\Http\Controllers;

use App\Models\InitailNo;
use App\Models\Outgoing;
use App\Models\OutgoingDetail;
use App\Models\OutgoingLocation;
use App\Models\OutgoingSaleLocation;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Location;
use App\Models\AreaLocation;
use App\Models\SalesReturn;
use App\Models\PurchaseReturn;
use App\Models\LotNo;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class OutgoingController extends Controller
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

            $query = Outgoing::select(
                'id',
                'date',
                'ref_no',
                'sr_id',
                'pr_id',
                'category',
                'order_id'
            )->with(['sales_return', 'purchase_return', 'order']);

            // Apply search if a search term is provided
            if (!empty($search)) {
                $searchLower = strtolower($search);
                $query->where(function ($q) use ($searchLower) {
                    $q
                        ->where(
                            'date',
                            'like',
                            '%' . $searchLower . '%'
                        )
                        ->orWhere(
                            'ref_no',
                            'like',
                            '%' . $searchLower . '%'
                        )
                        ->orWhereHas('sales_return', function ($query) use (
                            $searchLower
                        ) {
                            $query->where(
                                'ref_no',
                                'like',
                                '%' . $searchLower . '%'
                            );
                        })
                        ->orWhereHas('sales_return.customer', function ($query) use (
                            $searchLower
                        ) {
                            $query->where(
                                'name',
                                'like',
                                '%' . $searchLower . '%'
                            );
                        })
                        ->orWhereHas('purchase_return', function ($query) use (
                            $searchLower
                        ) {
                            $query->where(
                                'grd_no',
                                'like',
                                '%' . $searchLower . '%'
                            );
                        })
                        ->orWhereHas('purchase_return.supplier', function (
                            $query
                        ) use ($searchLower) {
                            $query->where(
                                'name',
                                'like',
                                '%' . $searchLower . '%'
                            );
                        })
                        ->orWhereHas('order', function ($query) use (
                            $searchLower
                        ) {
                            $query->where(
                                'order_no',
                                'like',
                                '%' . $searchLower . '%'
                            );
                        })
                        ->orWhereHas('order.customers', function ($query) use (
                            $searchLower
                        ) {
                            $query->where(
                                'name',
                                'like',
                                '%' . $searchLower . '%'
                            );
                        });
                });
            }
            $results = null;

            if (!empty($columnsData)) {
                $sortableColumns = [
                    1 => 'date',
                    2 => 'ref_no',
                    3 => ['sr_id', 'pr_id', 'order_id'],
                    4 => ['sr_id', 'pr_id', 'order_id'],
                    // Add more columns as needed
                ];

                if ($orderByColumnIndex != null) {
                    if ($orderByColumnIndex == '0') {
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
                $results = $query
                    ->where(function ($q) use ($columnsData) {
                        foreach ($columnsData as $column) {
                            $searchLower = strtolower($column['value']);

                            switch ($column['index']) {
                                case 1:
                                    $q->where(
                                        'date',
                                        'like',
                                        '%' . $searchLower . '%'
                                    );


                                    break;
                                case 2:
                                    $q->where(
                                        'ref_no',
                                        'like',
                                        '%' . $searchLower . '%'
                                    );

                                    break;
                                case 3:
                                    $q->whereHas('sales_return', function (
                                        $query
                                    ) use ($searchLower) {
                                        $query->where(
                                            'ref_no',
                                            'like',
                                            '%' . $searchLower . '%'
                                        );
                                    });
                                    $q->whereHas('purchase_return', function (
                                        $query
                                    ) use ($searchLower) {
                                        $query->where(
                                            'grd_no',
                                            'like',
                                            '%' . $searchLower . '%'
                                        );
                                    });
                                    $q->whereHas(
                                        'order',
                                        function ($query) use ($searchLower) {
                                            $query->where(
                                                'order_no',
                                                'like',
                                                '%' . $searchLower . '%'
                                            );
                                        }
                                    );

                                    break;
                                case 4:
                                    $q->whereHas('sales_return.customer', function (
                                        $query
                                    ) use ($searchLower) {
                                        $query->where(
                                            'name',
                                            'like',
                                            '%' . $searchLower . '%'
                                        );
                                    });
                                    $q->whereHas('purchase_return.supplier', function (
                                        $query
                                    ) use ($searchLower) {
                                        $query->where(
                                            'name',
                                            'like',
                                            '%' . $searchLower . '%'
                                        );
                                    });
                                    $q->whereHas(
                                        'order.customers',
                                        function ($query) use ($searchLower) {
                                            $query->where(
                                                'name',
                                                'like',
                                                '%' . $searchLower . '%'
                                            );
                                        }
                                    );
                                    break;



                                default:
                                    break;
                            }
                        }
                    })
                    ->orderBy($orderByColumn, $orderByDirection)
                    ->get();
            }

            // type_of_rejection and format the results for DataTables
            $recordsTotal = $results ? $results->count() : 0;

            // Check if there are results before applying skip and take
            if ($results->isNotEmpty()) {
                $uom = $results
                    ->skip($start)
                    ->take($length)
                    ->all();
            } else {
                $uom = [];
            }

            $index = 0;
            foreach ($uom as $row) {

                $row->sr_no = $start + $index + 1;
                $row->date = date('d-m-Y', strtotime($row->date));
                if ($row->category == 1) {
                    $row->number =  $row->sales_return->ref_no ?? '';
                    $row->vendor_or_customer = $row->sales_return->customer->name ?? '';
                } elseif ($row->category == 2) {
                    $row->number =  $row->purchase_return->grd_no ?? '';
                    $row->vendor_or_customer =  $row->purchase_return->supplier->name ?? '';
                } elseif ($row->category == 3) {
                    $row->number = $row->order->order_no ?? '';
                    $row->vendor_or_customer = $row->order->customers->name ?? '';
                }


                $row->action =
                    '<div class="d-flex"><a class="btn btn-success btn-sm mx-2" href="' . route('outgoing.view', $row->id) . '"><i
                                            class="bi bi-eye"></i></a>
                                    <a class="btn btn-danger btn-sm mx-2" href="' . route('outgoing.preview', $row->id) . '"
                                        target="_blank"><i class="bi bi-file-pdf"></i></a>
                                    <a class="btn btn-info btn-sm mx-2" href="' . route('outgoing.edit', $row->id) . '"><i
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
                                                <form method="POST" action="' . route('outgoing.destroy', $row->id) . '">
                                                    ' . csrf_field() . '
                                                    ' . method_field('DELETE') . '
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div></div>';
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

            $query = Outgoing::select(
                'id',
                'date',
                'ref_no',
                'sr_id',
                'pr_id',
                'category',
                'order_id'
            )->with(['sales_return', 'purchase_return', 'order']);

            // Apply search if a search term is provided
            if (!empty($search)) {
                $searchLower = strtolower($search);
                $query->where(function ($q) use ($searchLower) {
                    $q
                        ->where(
                            'date',
                            'like',
                            '%' . $searchLower . '%'
                        )
                        ->orWhere(
                            'ref_no',
                            'like',
                            '%' . $searchLower . '%'
                        )
                        ->orWhereHas('sales_return', function ($query) use (
                            $searchLower
                        ) {
                            $query->where(
                                'ref_no',
                                'like',
                                '%' . $searchLower . '%'
                            );
                        })
                        ->orWhereHas('sales_return.customer', function ($query) use (
                            $searchLower
                        ) {
                            $query->where(
                                'name',
                                'like',
                                '%' . $searchLower . '%'
                            );
                        })
                        ->orWhereHas('purchase_return', function ($query) use (
                            $searchLower
                        ) {
                            $query->where(
                                'grd_no',
                                'like',
                                '%' . $searchLower . '%'
                            );
                        })
                        ->orWhereHas('purchase_return.supplier', function (
                            $query
                        ) use ($searchLower) {
                            $query->where(
                                'name',
                                'like',
                                '%' . $searchLower . '%'
                            );
                        })
                        ->orWhereHas('order', function ($query) use (
                            $searchLower
                        ) {
                            $query->where(
                                'order_no',
                                'like',
                                '%' . $searchLower . '%'
                            );
                        })
                        ->orWhereHas('order.customers', function ($query) use (
                            $searchLower
                        ) {
                            $query->where(
                                'name',
                                'like',
                                '%' . $searchLower . '%'
                            );
                        });
                });
            }

            if (!empty($columnsData)) {
                $sortableColumns = [
                    1 => 'date',
                    2 => 'ref_no',
                    3 => ['sr_id', 'pr_id', 'order_id'],
                    4 => ['sr_id', 'pr_id', 'order_id'],
                    // Add more columns as needed
                ];
            }
            if ($orderByColumnIndex != null) {
                if ($orderByColumnIndex != '0') {
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

            $uom->each(function ($row, $index) use (&$start) {
                $row->sr_no = $start + $index + 1;
                $row->date = date('d-m-Y', strtotime($row->date));

                $row->number = '';
                $row->vendor_or_customer = '';

                if ($row->category == 1) {
                    $row->number =  $row->sales_return->ref_no ?? '';
                    $row->vendor_or_customer = $row->sales_return->customer->name ?? '';
                } elseif ($row->category == 2) {
                    $row->number =  $row->purchase_return->grd_no ?? '';
                    $row->vendor_or_customer =  $row->purchase_return->supplier->name ?? '';
                } elseif ($row->category == 3) {
                    $row->number = $row->order->order_no ?? '';
                    $row->vendor_or_customer = $row->order->customers->name ?? '';
                }

                // dd($row);


                $row->action =
                    '<div class="d-flex"><a class="btn btn-success btn-sm mx-2" href="' . route('outgoing.view', $row->id) . '"><i
                                            class="bi bi-eye"></i></a>
                                    <a class="btn btn-danger btn-sm mx-2" href="' . route('outgoing.preview', $row->id) . '"
                                        target="_blank"><i class="bi bi-file-pdf"></i></a>
                                    <a class="btn btn-info btn-sm mx-2" href="' . route('outgoing.edit', $row->id) . '"><i
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
                                                <form method="POST" action="' . route('outgoing.destroy', $row->id) . '">
                                                    ' . csrf_field() . '
                                                    ' . method_field('DELETE') . '
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div></div>';
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
            Auth::user()->hasPermissionTo('Outgoing List') ||
            Auth::user()->hasPermissionTo('Outgoing Create') ||
            Auth::user()->hasPermissionTo('Outgoing Edit') ||
            Auth::user()->hasPermissionTo('Outgoing View') ||
            Auth::user()->hasPermissionTo('Outgoing Preview') ||
            Auth::user()->hasPermissionTo('Outgoing Delete')
        ) {
            $outgoings = Outgoing::all();
            return view('wms.operations.outgoing.index', compact('outgoings'));
        }
        return back()->with('custom_errors', 'You don`t have the right permission');
    }
    public function create()
    {
        if (!Auth::user()->hasPermissionTo('Outgoing Create')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $ref_no = '';
        $year = Carbon::now('Asia/Kuala_Lumpur')->format('y');
        $setting = InitailNo::where('screen', 'Delivery Order')->first();

        if ($setting) {
            $stock = Outgoing::orderBy('id', 'DESC')->first();
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
            $stock = Outgoing::orderBy('id', 'DESC')->first();
            if ($stock) {
                // Extract running_no from $stock->ref_no which is in format 'SR/running_no/year'
                $parts = explode('/', $stock->ref_no);
                if (count($parts) == 3) {
                    $running_no = (int) $parts[1] + 1;
                } else {
                    $running_no = 1; // Fallback in case the format is unexpected
                }
                $ref_no = 'DO/' . $running_no . '/' . $year;
            } else {
                $ref_no = 'DO/1/' . $year;
            }
        }
        $locations = Location::select('area_id', 'rack_id', 'level_id', 'product_id', 'lot_no', 'used_qty')->with('area', 'rack', 'level')->get();
        $orders = Order::with(['customers', 'order_detail', 'order_detail.products', 'order_detail.products.units'])->get();
        $sales_returns = SalesReturn::with(['customer', 'sales_return_detail', 'sales_return_detail.product', 'sales_return_detail.product.units'])->get();
        $purchase_returns = PurchaseReturn::with(['supplier', 'purchase_return_detail', 'purchase_return_detail.product', 'purchase_return_detail.product.units'])->get();
        return view('wms.operations.outgoing.create', compact('orders', 'sales_returns', 'purchase_returns', 'ref_no', 'locations'));
    }

    public function store(Request $request)
    {
        if (!Auth::user()->hasPermissionTo('Outgoing Create')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }

        $validated = $request->validate([
            'category' => [
                'required'
            ],
            'sr_id' => [
                'required_if:category,1',
                Rule::unique('outgoings', 'sr_id')->whereNull('deleted_at')
            ],
            'pr_id' => [
                'required_if:category,2',
                Rule::unique('outgoings', 'pr_id')->whereNull('deleted_at')
            ],
            'order_id' => [
                'required_if:category,3',
                Rule::unique('outgoings', 'order_id')->whereNull('deleted_at')
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

        $outgoing = new Outgoing();
        if ($request->category == 1) {
            $outgoing->sr_id = $request->sr_id;
        } else if ($request->category == 2) {
            $outgoing->pr_id = $request->pr_id;
        } else if ($request->category == 3) {
            $outgoing->order_id = $request->order_id;
        }
        $outgoing->category = $request->category;
        $outgoing->ref_no = $request->ref_no;
        $outgoing->date = $request->date;
        $outgoing->acc_no = $request->acc_no;
        $outgoing->payment_term = $request->payment_term;
        $outgoing->mode = $request->mode;
        $outgoing->created_by = Auth::user()->id;
        $outgoing->save();

        foreach ($request->products as $products) {
            $outgoing_detail = new OutgoingDetail();
            $outgoing_detail->outgoing_id = $outgoing->id;
            $outgoing_detail->product_id = $products['product_id'] ?? null;
            $outgoing_detail->remarks = $products['remarks'] ?? null;
            $outgoing_detail->qty = $products['qty'] ?? 0;
            $outgoing_detail->save();
        }

        if ($request->category == 3 && $request->order_id) {
            $orderId = $request->order_id;
            $totalOutgoingQty = collect($request->products)->sum('qty');
            $orderDetailsTotalQty = OrderDetail::where('order_id', $orderId)
                ->selectRaw('SUM(firm_qty + n1_qty + n2_qty + n3_qty) as total_qty')
                ->value('total_qty');
            if ($totalOutgoingQty >= $orderDetailsTotalQty) {
                $order = Order::find($orderId);
                if ($order) {
                    $order->status = 'complete';
                    $order->save();
                }
            }
        }

        $storedData = json_decode($request->input('details'), true);

        $newArray = collect($storedData)->flatMap(function ($subArray) {
            return $subArray;
        })->sortBy('hiddenId')->values()->toArray();

        foreach ($newArray as $key => $value) {
            $lot_no = new LotNo();
            $lot_no->lot_no = $value['lot_no'] ?? null;
            $lot_no->save();

            $detail = new OutgoingLocation();
            $detail->outgoing_id = $outgoing->id;
            $detail->product_id = $value['hiddenId'] ?? null;
            $detail->area_id = $value['area'] ?? null;
            $detail->rack_id = $value['rack'] ?? null;
            $detail->level_id = $value['level'] ?? null;
            $detail->available_qty = $value['available_qty'] ?? 0;
            $detail->lot_no = $value['lot_no'] ?? null;
            $detail->qty = $value['qty'] ?? 0;
            $detail->save();

            $location = Location::where('area_id', $detail->area_id)->where('rack_id', $detail->rack_id)->where('level_id', $detail->level_id)->where('product_id', $detail->product_id)->where('lot_no', $detail->lot_no)->first();
            if ($location) {
                $location->used_qty -= $detail->qty ?? 0;
                $location->save();
            }
        }


        NotificationController::Notification(
            'Outgoing',
            'Create',
            route('outgoing.index', $outgoing->id)
        );

        return redirect()->route('outgoing.index')->with('custom_success', 'Outgoing Created Successfully.');
    }

    public function edit(Request $request, $id)
    {
        if (!Auth::user()->hasPermissionTo('Outgoing Edit')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $outgoing = Outgoing::find($id);
        $outgoing_details = OutgoingDetail::where('outgoing_id', $id)->get();
        $outgoing_locations = OutgoingLocation::where('outgoing_id', $id)->get();
        $locations = Location::select('area_id', 'rack_id', 'level_id', 'product_id', 'lot_no', 'used_qty')->with('area', 'rack', 'level')->get();
        $orders = Order::with(['customers', 'order_detail', 'order_detail.products', 'order_detail.products.units'])->get();
        $sales_returns = SalesReturn::with(['customer', 'sales_return_detail', 'sales_return_detail.product', 'sales_return_detail.product.units'])->get();
        $purchase_returns = PurchaseReturn::with(['supplier', 'purchase_return_detail', 'purchase_return_detail.product', 'purchase_return_detail.product.units'])->get();
        return view('wms.operations.outgoing.edit', compact('orders', 'sales_returns', 'purchase_returns', 'outgoing', 'outgoing_details', 'outgoing_locations', 'locations'));
    }

    public function update(Request $request, $id)
    {
        if (!Auth::user()->hasPermissionTo('Outgoing Edit')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }

        $validated = $request->validate([
            'category' => [
                'required'
            ],
            'sr_id' => [
                'required_if:category,1',
                Rule::unique('outgoings', 'sr_id')->whereNull('deleted_at')->ignore($id)
            ],
            'pr_id' => [
                'required_if:category,2',
                Rule::unique('outgoings', 'pr_id')->whereNull('deleted_at')->ignore($id)
            ],
            'order_id' => [
                'required_if:category,3',
                Rule::unique('outgoings', 'order_id')->whereNull('deleted_at')->ignore($id)
            ],
            'date' => [
                'required'
            ],
            'products' => [
                'required'
            ]
        ]);

        $outgoing = Outgoing::find($id);
        $details = OutgoingDetail::where('outgoing_id', '=', $id)->get();
        $detailIds = $details->pluck('product_id')->toArray();
        $existingDetails = OutgoingLocation::whereIn('product_id', $detailIds)->get();
        foreach ($existingDetails as $existingDetail) {
            if ($existingDetail->area_id != null && $existingDetail->rack_id != null && $existingDetail->level_id != null) {
                $location = Location::where('area_id', $existingDetail->area_id)->where('rack_id', $existingDetail->rack_id)->where('level_id', $existingDetail->level_id)->where('product_id', $existingDetail->product_id)->where('lot_no', $existingDetail->lot_no)->first();

                if ($location) {
                    $location->used_qty += $existingDetail->qty ?? 0;
                    $location->save();
                }
            }
        }
        OutgoingLocation::where('outgoing_id', $id)->delete();

        if ($request->category == 1) {
            $outgoing->sr_id = $request->sr_id;
        } else if ($request->category == 2) {
            $outgoing->pr_id = $request->pr_id;
        } else if ($request->category == 3) {
            $outgoing->order_id = $request->order_id;
        }
        $outgoing->category = $request->category;
        $outgoing->date = $request->date;
        $outgoing->acc_no = $request->acc_no;
        $outgoing->payment_term = $request->payment_term;
        $outgoing->mode = $request->mode;
        $outgoing->created_by = Auth::user()->id;
        $outgoing->save();

        OutgoingDetail::where('outgoing_id', $id)->delete();
        foreach ($request->products as $products) {
            $outgoing_detail = new OutgoingDetail();
            $outgoing_detail->outgoing_id = $outgoing->id;
            $outgoing_detail->product_id = $products['product_id'] ?? null;
            $outgoing_detail->remarks = $products['remarks'] ?? null;
            $outgoing_detail->qty = $products['qty'] ?? 0;
            $outgoing_detail->save();
        }

        $storedData = json_decode($request->input('details'), true);

        $newArray = collect($storedData)->flatMap(function ($subArray) {
            return $subArray;
        })->sortBy('hiddenId')->values()->toArray();

        foreach ($newArray as $key => $value) {
            $lot_no = new LotNo();
            $lot_no->lot_no = $value['lot_no'] ?? null;
            $lot_no->save();

            $detail = new OutgoingLocation();
            $detail->outgoing_id = $outgoing->id;
            $detail->product_id = $value['hiddenId'] ?? null;
            $detail->area_id = $value['area'] ?? null;
            $detail->rack_id = $value['rack'] ?? null;
            $detail->level_id = $value['level'] ?? null;
            $detail->available_qty = $value['available_qty'] ?? 0;
            $detail->lot_no = $value['lot_no'] ?? null;
            $detail->qty = $value['qty'] ?? 0;
            $detail->save();

            $location = Location::where('area_id', $detail->area_id)->where('rack_id', $detail->rack_id)->where('level_id', $detail->level_id)->where('product_id', $detail->product_id)->where('lot_no', $detail->lot_no)->first();
            if ($location) {
                $location->used_qty -= $detail->qty ?? 0;
                $location->save();
            }
        }

        return redirect()->route('outgoing.index')->with('custom_success', 'Outgoing Updated Successfully.');
    }

    public function view(Request $request, $id)
    {
        if (!Auth::user()->hasPermissionTo('Outgoing View')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $outgoing = Outgoing::find($id);
        $outgoing_details = OutgoingDetail::where('outgoing_id', $id)->get();
        $outgoing_locations = OutgoingLocation::where('outgoing_id', $id)->get();
        $locations = Location::select('area_id', 'rack_id', 'level_id', 'product_id', 'lot_no', 'used_qty')->with('area', 'rack', 'level')->get();
        $orders = Order::where('status', 'complete')->with(['customers', 'order_detail', 'order_detail.products', 'order_detail.products.units'])->get();
        $sales_returns = SalesReturn::with(['customer', 'sales_return_detail', 'sales_return_detail.product', 'sales_return_detail.product.units'])->get();
        $purchase_returns = PurchaseReturn::with(['supplier', 'purchase_return_detail', 'purchase_return_detail.product', 'purchase_return_detail.product.units'])->get();
        return view('wms.operations.outgoing.view', compact('orders', 'sales_returns', 'purchase_returns', 'outgoing', 'outgoing_details', 'outgoing_locations', 'locations'));
    }

    public function preview(Request $request, $id)
    {
        if (!Auth::user()->hasPermissionTo('Outgoing Preview')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $outgoing = Outgoing::find($id);
        $outgoing_details = OutgoingDetail::where('outgoing_id', $id)->get();

        $pdf = FacadePdf::loadView('wms.operations.outgoing.preview', compact('outgoing', 'outgoing_details'))->setPaper('a4');
        return $pdf->stream('outgoing.preview');
    }

    public function destroy(Request $request, $id)
    {
        if (!Auth::user()->hasPermissionTo('Outgoing Delete')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $outgoing = Outgoing::find($id);
        $details = OutgoingDetail::where('outgoing_id', '=', $id)->get();
        $detailIds = $details->pluck('product_id')->toArray();
        $existingDetails = OutgoingLocation::whereIn('product_id', $detailIds)->get();
        foreach ($existingDetails as $existingDetail) {
            if ($existingDetail->area_id != null && $existingDetail->rack_id != null && $existingDetail->level_id != null) {
                $location = Location::where('area_id', $existingDetail->area_id)->where('rack_id', $existingDetail->rack_id)->where('level_id', $existingDetail->level_id)->where('product_id', $existingDetail->product_id)->where('lot_no', $existingDetail->lot_no)->first();

                if ($location) {
                    $location->used_qty += $existingDetail->qty ?? 0;
                    $location->save();
                }
            }
        }
        OutgoingLocation::where('outgoing_id', $id)->delete();

        $outgoing->delete();
        return redirect()->route('outgoing.index')->with('custom_success', 'Outgoing Deleted Successfully.');
    }
}
