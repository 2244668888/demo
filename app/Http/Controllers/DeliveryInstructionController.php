<?php

namespace App\Http\Controllers;

use App\Models\DeliveryInstruction;
use App\Models\DeliveryInstructionDetail;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class DeliveryInstructionController extends Controller
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

            $query = DeliveryInstruction::select('id', 'order_id', 'date', 'created_by')->with(['user', 'order']);

            // Apply search if a search term is provided
            if (!empty($search)) {
                $searchLower = strtolower($search);
                $query->where(function ($q) use ($searchLower) {
                      $q->whereHas('order', function ($query) use ($searchLower) {
                        $query->where('order_no', 'like', '%' . $searchLower . '%');
                    })
                    ->orWhereHas('order.customers', function ($query) use ($searchLower) {
                        $query->where('name', 'like', '%' . $searchLower . '%');
                    })
                    ->orWhereHas('order', function ($query) use ($searchLower) {
                        if (\Carbon\Carbon::hasFormat($searchLower, 'm-Y')) {
                            $searchDate = \Carbon\Carbon::createFromFormat('m-Y', $searchLower)->format('Y-m');
                        } else {
                            $searchDate = $searchLower;
                        }
                        $query->where('order_month', 'like', '%' . $searchDate . '%');
                    })
                    ->orWhereHas('user', function ($query) use ($searchLower) {
                        $query->where('user_name', 'like', '%' . $searchLower . '%');
                    })
                    ->orWhere(function ($query) use ($searchLower) {
                        if (\Carbon\Carbon::hasFormat($searchLower, 'd/m/Y')) {
                            $searchDate = \Carbon\Carbon::createFromFormat('d/m/Y', $searchLower)->format('d-m-Y');
                        } else {
                            $searchDate = $searchLower;
                        }
                        $query->where('date', 'like', '%' . $searchDate . '%');
                    });


                });
            }
            $results = null;

            if (!empty($columnsData)) {

                $sortableColumns = [
                    1 => 'order_id',
                    2 => 'order_id',
                    3 => 'order_id',
                    4 => 'date',
                    5 => 'created_by',




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
                                $q->WhereHas('order', function ($query) use ($searchLower) {
                                    $query->where('order_no', 'like', '%' . $searchLower . '%');
                                });
                                break;
                            case 2:
                                $q->WhereHas('order.customers', function ($query) use ($searchLower) {
                                    $query->where('name', 'like', '%' . $searchLower . '%');
                                });

                                break;
                                case 3:
                                    $q->WhereHas('order', function ($query) use ($searchLower) {
                                        if (\Carbon\Carbon::hasFormat($searchLower, 'm-Y')) {
                                            $searchDate = \Carbon\Carbon::createFromFormat('m-Y', $searchLower)->format('Y-m');
                                        } else {
                                            $searchDate = $searchLower;
                                        }
                                        $query->where('order_month', 'like', '%' . $searchDate . '%');
                                    });
                                    // dd($q->get());


                                    break;
                                    case 4:
                                        if (\Carbon\Carbon::hasFormat($searchLower, 'd/m/Y')) {
                                            $searchDate = \Carbon\Carbon::createFromFormat('d/m/Y', $searchLower)->format('d-m-Y');
                                        } else {
                                            $searchDate = $searchLower;
                                        }
                                        $q->where('date', 'like', '%' . $searchDate . '%');

                                break;
                            case 5:
                                $q->WhereHas('user', function ($query) use ($searchLower) {
                                    $query->where('user_name', 'like', '%' . $searchLower . '%');
                                });

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
                $row->date = Carbon::parse($row->date)->format('d/m/Y');
                $row->order->order_month = Carbon::parse($row->order->order_month)->format('m-Y');
                $row->customer = $row->order->customers->name ?? '';
                $row->action = '<div class="d-flex"><a class="btn btn-success btn-sm mx-2"
                                        href="' . route('delivery_instruction.view', $row->id) . '"><i
                                            class="bi bi-eye"></i></a>
                                    <a class="btn btn-info btn-sm mx-2"
                                        href="' . route('delivery_instruction.edit', $row->id) . '"><i
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
                                                <form method="POST" action="' . route('delivery_instruction.destroy', $row->id) . '">
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


            $query = DeliveryInstruction::select('id', 'order_id', 'date', 'created_by')->with(['user', 'order']);
            // Apply search if a search term is provided
            if (!empty($search)) {
                $searchLower = strtolower($search);
                $query->where(function ($q) use ($searchLower) {
                    $q->whereHas('order', function ($query) use ($searchLower) {
                        $query->where('order_no', 'like', '%' . $searchLower . '%');
                    })
                    ->orWhereHas('order.customers', function ($query) use ($searchLower) {
                        $query->where('name', 'like', '%' . $searchLower . '%');
                    })
                    ->orWhereHas('order', function ($query) use ($searchLower) {
                        if (\Carbon\Carbon::hasFormat($searchLower, 'm-Y')) {
                            $searchDate = \Carbon\Carbon::createFromFormat('m-Y', $searchLower)->format('Y-m');
                        } else {
                            $searchDate = $searchLower;
                        }
                        $query->where('order_month', 'like', '%' . $searchDate . '%');
                    })
                    ->orWhereHas('user', function ($query) use ($searchLower) {
                        $query->where('user_name', 'like', '%' . $searchLower . '%');
                    })
                    ->orWhere(function ($query) use ($searchLower) {
                        if (\Carbon\Carbon::hasFormat($searchLower, 'd/m/Y')) {
                            $searchDate = \Carbon\Carbon::createFromFormat('d/m/Y', $searchLower)->format('d-m-Y');
                        } else {
                            $searchDate = $searchLower;
                        }
                        $query->where('date', 'like', '%' . $searchDate . '%');
                    });


                });
            }


            $sortableColumns = [
                1 => 'order_id',
                2 => 'order_id',
                3 => 'order_id',
                4 => 'date',
                5 => 'created_by',




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
                $row->order->order_month = Carbon::parse($row->order->order_month)->format('m-Y');

                $row->customer = $row->order->customers->name ?? '';



                // $row->status = $status;

                $row->action = '<div class="d-flex"><a class="btn btn-success btn-sm mx-2"
                                        href="' . route('delivery_instruction.view', $row->id) . '"><i
                                            class="bi bi-eye"></i></a>
                                    <a class="btn btn-info btn-sm mx-2"
                                        href="' . route('delivery_instruction.edit', $row->id) . '"><i
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
                                                <form method="POST" action="' . route('delivery_instruction.destroy', $row->id) . '">
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
            Auth::user()->hasPermissionTo('Delivery Instruction List') ||
            Auth::user()->hasPermissionTo('Delivery Instruction Create') ||
            Auth::user()->hasPermissionTo('Delivery Instruction Edit') ||
            Auth::user()->hasPermissionTo('Delivery Instruction View') ||
            Auth::user()->hasPermissionTo('Delivery Instruction Delete')
        ) {
            $delivery_instructions = DeliveryInstruction::all();
            return view('wms.operations.delivery-instruction.index', compact('delivery_instructions'));
        }
        return back()->with('custom_errors', 'You don`t have the right permission');
    }
    public function create()
    {
        if (!Auth::user()->hasPermissionTo('Delivery Instruction Create')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $orders = Order::with('order_detail', 'customers', 'order_detail.products', 'order_detail.products.type_of_products')->get();
        return view('wms.operations.delivery-instruction.create', compact('orders'));
    }

    public function store(Request $request)
    {
        if (!Auth::user()->hasPermissionTo('Delivery Instruction Create')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $validated = $request->validate([
            'order_id' => [
                'required',
                Rule::unique('delivery_instructions', 'order_id')->whereNull('deleted_at')
            ]
        ]);

        $delivery = new DeliveryInstruction();
        $delivery->order_id = $request->order_id;
        $delivery->created_by = Auth::user()->id;
        $delivery->date = Carbon::now('Asia/Kuala_Lumpur')->format('d-m-Y');
        $delivery->save();

        if ($request->products) {
            $products = json_decode($request->products);
            foreach ($products as $key => $product) {
                $dayValue = json_encode($product);
                $order_detail = new DeliveryInstructionDetail();
                $order_detail->di_id = $delivery->id;
                $order_detail->product_id = $key;
                $order_detail->calendar = $dayValue;
                $order_detail->save();
            }
        }

        NotificationController::Notification(
            'Delivery Instruction',
            'Create',
            route('delivery_instruction.view', $delivery->id)
        );

        return redirect()->route('delivery_instruction.index')->with('custom_success', 'Delivery Instruction Created Successfully.');
    }

    public function edit($id)
    {
        if (!Auth::user()->hasPermissionTo('Delivery Instruction Edit')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $delivery_instruction = DeliveryInstruction::find($id);
        $delivery_instruction_details = DeliveryInstructionDetail::where('di_id', $id)->get();
        $orders = Order::with('order_detail', 'customers', 'order_detail.products', 'order_detail.products.type_of_products')->get();
        return view('wms.operations.delivery-instruction.edit', compact('orders', 'delivery_instruction', 'delivery_instruction_details'));
    }

    public function update(Request $request, $id)
    {
        if (!Auth::user()->hasPermissionTo('Delivery Instruction Edit')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $validated = $request->validate([
            'order_id' => [
                'required',
                Rule::unique('delivery_instructions', 'order_id')->whereNull('deleted_at')->ignore($id)
            ]
        ]);

        $delivery = DeliveryInstruction::find($id);
        $delivery->order_id = $request->order_id;
        $delivery->created_by = Auth::user()->id;
        // Only update the 'date' field if it's provided in the request
        if ($request->has('date') && $request->date) {
            $delivery->date = Carbon::parse($request->date)->format('d-m-Y');
        } else {
            // Use the existing date if not provided
            $delivery->date = $delivery->date; // retain the current date value
        }
        
        $delivery->save();

        DeliveryInstructionDetail::where('di_id', $id)->delete();

        if ($request->products) {
            $products = json_decode($request->products);
            foreach ($products as $key => $product) {
                $dayValue = json_encode($product);
                $order_detail = new DeliveryInstructionDetail();
                $order_detail->di_id = $delivery->id;
                $order_detail->product_id = $key;
                $order_detail->calendar = $dayValue;
                $order_detail->save();
            }
        }

        return redirect()->route('delivery_instruction.index')->with('custom_success', 'Delivery Instruction Updated Successfully.');
    }

    public function view($id)
    {
        if (!Auth::user()->hasPermissionTo('Delivery Instruction View')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $delivery_instruction = DeliveryInstruction::find($id);
        $delivery_instruction_details = DeliveryInstructionDetail::where('di_id', $id)->get();
        $orders = Order::with('order_detail', 'customers', 'order_detail.products', 'order_detail.products.type_of_products')->get();
        return view('wms.operations.delivery-instruction.view', compact('orders', 'delivery_instruction', 'delivery_instruction_details'));
    }

    public function destroy(Request $request, $id)
    {
        if (!Auth::user()->hasPermissionTo('Delivery Instruction Delete')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $delivery_instruction = DeliveryInstruction::find($id);
        DeliveryInstructionDetail::where('di_id', $id)->delete();
        $delivery_instruction->delete();
        return redirect()->route('delivery_instruction.index')->with('custom_success', 'Delivery Instruction Deleted Successfully.');
    }
}
