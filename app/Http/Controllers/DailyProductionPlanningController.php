<?php

namespace App\Http\Controllers;

use App\Models\Bom;
use App\Models\BomProcess;
use App\Models\Customer;
use App\Models\DailyProductionChildPart;
use App\Models\DailyProductionPlanning;
use App\Models\DailyProductionPlanningDetail;
use App\Models\DailyProductionProduct;
use App\Models\DeliveryInstruction;
use App\Models\InitailNo;
use App\Models\Machine;
use App\Models\MachineTonnage;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class DailyProductionPlanningController extends Controller
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

            $query = DailyProductionPlanning::select('id', 'order_id', 'customer_id','planning_date','created_by','created_date', 'status')->with('order');


            // Apply search if a search term is provided
            if (!empty($search)) {
                $searchLower = strtolower($search);
                $query->where(function ($q) use ($searchLower) {
                    $q

                        ->whereHas('order', function ($query) use ($searchLower) {
                            $query->where('order_no', 'like', '%' . $searchLower . '%');
                        })
                        ->orWhereHas('order.customers', function ($query) use ($searchLower) {
                            $query->where('name', 'like', '%' . $searchLower . '%');
                        })
                        ->orWhereHas('order', function ($query) use ($searchLower) {
                            $query->where('order_month', 'like', '%' . $searchLower . '%');
                        })
                        ->orWhere('planning_date', 'like', '%' . $searchLower . '%')
                        ->orWhereHas('users', function ($query) use ($searchLower) {
                            $query->where('user_name', 'like', '%' . $searchLower . '%');
                        })

                        ->orWhere('created_date', 'like', '%' . $searchLower . '%')
                        ->orWhere('status', 'like', '%' . $searchLower . '%');
                });
            }
            $results = null;

            if (!empty($columnsData)) {

                $sortableColumns = [
                    1 => 'order_id',
                    2 => 'customer_id',
                    3 => 'planning_date',
                    4 => 'created_by',
                    5 => 'created_date',
                    6 => 'status',


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
                                $q->whereHas('order', function ($query) use ($searchLower) {
                                    $query->where('order_no', 'like', '%' . $searchLower . '%');
                                });

                                break;
                            case 2:
                                $q->whereHas('order.customers', function ($query) use ($searchLower) {
                                    $query->where('name', 'like', '%' . $searchLower . '%');
                                });

                                break;
                                case 3:
                                    $q->whereHas('order', function ($query) use ($searchLower) {
                                        $query->where('order_month', 'like', '%' . $searchLower . '%');
                                    });


                                    break;
                                    case 4:

                                        $q->where('planning_date', 'like', '%' . $searchLower . '%');
                                        break;

                                        case 5:
                                            $q->whereHas('users', function ($query) use ($searchLower) {
                                                $query->where('user_name', 'like', '%' . $searchLower . '%');
                                            });
                                        break;
                                        case 6:
                                            $q->where('created_date', 'like', '%' . $searchLower . '%');

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

                $row->customer = $row->order->customers->name;
                $row->user = $row->users->user_name;
                // dd($row->status);


                $row->action .= '<div class="d-flex"><a class="btn btn-success btn-sm mx-1" title="View" href="' . route('daily-production-planning.view', $row->id) . '"><i
                                    class="bi bi-eye"></i></a>
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
                                                <form method="POST" action="' . route('daily-production-planning.destroy', $row->id) . '">
                                                    ' . csrf_field() . '
                                                    ' . method_field('DELETE') . '
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>';
                // $row->status = $status;
                if ($row->status == 'In Progress'){
                    $row->action .= '<a class="btn btn-info btn-sm mx-1" title="Edit" href="' . route('daily-production-planning.edit', $row->id) . '"><i
                                        class="bi bi-pencil"></i></a></div>';
                }

                if ($row->status == "In Progress") {
                    $row->status = '<span class="badge border border-warning text-warning">In Progress</span>';
                } elseif ($row->status == 'Completed') {
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

            $query = DailyProductionPlanning::select('id', 'order_id', 'customer_id','planning_date','created_by','created_date', 'status')->with('order');


            // Apply search if a search term is provided
            if (!empty($search)) {
                $searchLower = strtolower($search);
                $query->where(function ($q) use ($searchLower) {
                    $q

                        ->whereHas('order', function ($query) use ($searchLower) {
                            $query->where('order_no', 'like', '%' . $searchLower . '%');
                        })
                        ->orWhereHas('order.customers', function ($query) use ($searchLower) {
                            $query->where('name', 'like', '%' . $searchLower . '%');
                        })
                        ->orWhereHas('order', function ($query) use ($searchLower) {
                            $query->where('order_month', 'like', '%' . $searchLower . '%');
                        })
                        ->orWhere('planning_date', 'like', '%' . $searchLower . '%')
                        ->orWhereHas('users', function ($query) use ($searchLower) {
                            $query->where('user_name', 'like', '%' . $searchLower . '%');
                        })

                        ->orWhere('created_date', 'like', '%' . $searchLower . '%')
                        ->orWhere('status', 'like', '%' . $searchLower . '%');
                });
            }

            $sortableColumns = [
                1 => 'order_id',
                2 => 'customer_id',
                3 => 'planning_date',
                4 => 'created_by',
                5 => 'created_date',
                6 => 'status',


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

                $row->action .= '<div class="d-flex"><a class="btn btn-success btn-sm mx-1" title="View" href="' . route('daily-production-planning.view', $row->id) . '"><i
                                    class="bi bi-eye"></i></a> <a class="btn btn-danger btn-sm mx-1" href="' . route('daily-production-planning.destroy', $row->id) . '" title="Delete"><i
                                    class="bi bi-trash" ></i></a>';
                // $row->status = $status;
                if ($row->status == 'In Progress'){
                    $row->action .= '<a class="btn btn-info btn-sm mx-1" title="Edit" href="' . route('daily-production-planning.edit', $row->id) . '"><i
                                        class="bi bi-pencil"></i></a></div>';
                }

                if ($row->status == "In Progress") {
                    $row->status = '<span class="badge border border-warning text-warning">In Progress</span>';
                } elseif ($row->status == 'Completed') {
                    $row->status = '<span class="badge border border-success text-success">Completed</span>';
                }
                $row->customer = $row->order->customers->name;
                $row->user = $row->users->user_name;


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
        if (!Auth::user()->hasPermissionTo('Daily Production Planning List') || !Auth::user()->hasPermissionTo('Daily Production Planning Create') || !Auth::user()->hasPermissionTo('Daily Production Planning Edit') || !Auth::user()->hasPermissionTo('Daily Production Planning View') || !Auth::user()->hasPermissionTo('Daily Production Planning Delete')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $dpps = DailyProductionPlanning::with('order.customers','users')->get();
        return view('mes.ppc.daily-production-planning.index', compact('dpps'));
    }
    public function create(){
        if (!Auth::user()->hasPermissionTo('Daily Production Planning Create')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $orders = Order::with('customers')->get();
        $customers = Customer::all();
        $setting = InitailNo::where('screen', 'Daily Production Planning')->first();
        $year = Carbon::now('Asia/Kuala_Lumpur')->format('y');

        if ($setting) {
            $dpp_ref = DailyProductionPlanning::orderBy('id', 'DESC')->first();
            if ($dpp_ref) {
                // Extract running_no from $dpp_ref->dpp_ref_no which is in format 'dpp/ref/running_no/year'
                $parts = explode('/', $dpp_ref->ref_no);
                if (count($parts) == 3) {
                    $running_no = (int) $parts[1] + 1;
                } else {
                    $running_no = 1; // Fallback in case the format is unexpected
                }
                $dpp_ref_no = $setting->ref_no . '/' . $running_no . '/' . $year;
            } else {
                $dpp_ref_no = $setting->ref_no . '/' . $setting->running_no . '/' . $year;
            }
        } else {
            $dpp_ref = DailyProductionPlanning::orderBy('id', 'DESC')->first();
            if ($dpp_ref) {
                // Extract running_no from $dpp_ref->ref_no which is in format 'dpp/ref/running_no/year'
                $parts = explode('/', $dpp_ref->ref_no);
                if (count($parts) == 3) {
                    $running_no = (int) $parts[1] + 1;
                } else {
                    $running_no = 1; // Fallback in case the format is unexpected
                }
                $dpp_ref_no = 'DPP/' . $running_no . '/' . $year;
            } else {
                $dpp_ref_no = 'DPP/1/' . $year;
            }
        }
        return view('mes.ppc.daily-production-planning.create', compact('orders','dpp_ref_no','customers'));
    }

    public function get_date(Request $request){
        $delievry_intructions = DeliveryInstruction::with('delivery_instruction_details','order')->where('order_id','=',$request->order_id)->get();
        return response()->json($delievry_intructions); // Return options as JSON
    }

    public function generate(Request $request){
        if (!Auth::user()->hasPermissionTo('Daily Production Planning Create')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $order_id = $request->order_id;
        $planning_date = $request->planning_date;
        $created_date = $request->created_date;
        $ref_no = $request->ref_no;
        $status = $request->status;
        $validated = $request->validate([
            'order_id' =>'required',
            'planning_date' =>'required',
            'created_date' =>'required',
            'ref_no' =>'required',
            'status' =>'required',
        ]);
        $customers = Customer::all();
        $products = Product::all();
        $orders = Order::with('customers')->get();
        $fetch_orders = Order::with('order_detail.products.type_of_products','customers')->where('id', $order_id)->first();
        $delievry_intructions = DeliveryInstruction::with('delivery_instruction_details','order')->where('order_id','=',$fetch_orders->id)->get();
        $product_ids = $fetch_orders->order_detail->pluck('product_id');
        $inventory = DB::table('locations')
            ->whereIn('product_id', $product_ids)
            ->select('product_id', DB::raw('SUM(used_qty) as qty'))
            ->groupBy('product_id')
            ->get();
        $users = User::all();
        $machines = Machine::all();
        $machine_tonnages = MachineTonnage::all();
        if ($delievry_intructions->isEmpty()) {
            return back()->with('custom_errors', 'Delivery Instruction not found for this Order');
        }
        return view('mes.ppc.daily-production-planning.create', compact('orders','fetch_orders','ref_no','customers','planning_date','created_date','status','delievry_intructions','inventory','products','users','machines','machine_tonnages'));

    }

    public function get_subparts(Request $request){
        $bom = new BomController();
        $bom_trees = [];
        foreach($request->ids as $id){
            $bom_trees[] = $bom->loadBomTree($id);
        }
        return response()->json($bom_trees);
    }
    public function get_bom_process(Request $request)
    {
        $product_ids = $request->product_ids;
        $processes = [];
        foreach ($product_ids as $product_id) {
            $processes[] = DailyProductionPlanningController::loadProcesses(
                $product_id
            );
        }
        if ($request->sub_part_ids) {
            foreach ($request->sub_part_ids as $sub_part_id) {
                $processes[] = DailyProductionPlanningController::loadProcesses(
                    $sub_part_id
                );
            }
        }
        return response()->json($processes);
    }

    static public function loadProcesses($productId, &$visitedProducts = [])
        {
            // Avoid infinite loops by tracking visited products
            if (in_array($productId, $visitedProducts)) {
                return null;
            }

            // Fetch BOM with necessary relationships including processes
            $bom = Bom::where('product_id', $productId)
                ->where('status', 'Verified')
                ->with(['processes.process','processes.machineTonnage','subParts.product','products.type_of_products'])
                ->first();

            $subPartsProcesses = [];

            // Check if the current product has subparts and fetch their processes
            if ($bom && $bom->subParts->isNotEmpty()) {
                foreach ($bom->subParts as $subPart) {
                    $subPartProcesses = DailyProductionPlanningController::loadProcesses($subPart->product_id, $visitedProducts);
                    $hasBom = $subPartProcesses !== null; // Check if subpart has a BOM
                    $subPartsProcesses[] = [
                        'subPart' => $subPart,
                        'hasBom' => $hasBom,
                        'processes' => $subPartProcesses,
                    ];
                }
            }

            if ($bom) {
                $visitedProducts[] = $productId;  // Mark this product as visited
                $processTree = [
                    'bom' => $bom,
                    'processes' => $bom->processes, // Include processes
                    'subParts' => $subPartsProcesses, // Include subparts with their processes
                ];

                return $processTree;
            }

            return null;
        }

    public function get_inventory_qty(Request $request){
        $product_id = $request->product_id;
        $inventory = DB::table('locations')
            ->where('product_id', $product_id)
            ->select('product_id', DB::raw('SUM(used_qty) as qty'))
            ->groupBy('product_id')
            ->get();
        return response()->json($inventory);

    }
    public function store(Request $request){
        $dpp = new DailyProductionPlanning();
        $dpp->order_id= $request->order_id;
        $dpp->customer_id= $request->customer_id;
        $dpp->planning_date= $request->planning_date;
        $dpp->created_date= $request->created_date;
        $dpp->created_by= Auth::user()->id;
        $dpp->ref_no= $request->ref_no;
        $dpp->status= $request->status;
        $dpp->save();
        $dpp_id = $dpp->id;

        foreach($request->product_table as $product_table){
            $dppp = new DailyProductionProduct();
            $dppp->dpp_id = $dpp_id;
            $dppp->product_id = $product_table['product_id'];
            $dppp->di_qty = $product_table['di_qty'];
            $dppp->inventory_qty = $product_table['inventory_qty'];
            $dppp->total_required_qty = $product_table['total_required_qty'];
            $dppp->est_plan_qty = $product_table['est_plan_qty'];
            $dppp->save();
        }
        foreach($request->childpart_table as $childpart_table){
            $dpcp = new DailyProductionChildPart();
            $dpcp->dpp_id = $dpp_id;
            $dpcp->product_id = $childpart_table['product_id'];
            $dpcp->parent_part_id = $childpart_table['parent_part_id'];
            $dpcp->subpart_qty = $childpart_table['subpart_qty'];
            $dpcp->inventory_qty = $childpart_table['inventory_qty'];
            $dpcp->total_required_qty = $childpart_table['total_required_qty'];
            $dpcp->est_plan_qty = $childpart_table['est_plan_qty'];

            $dpcp->save();


            NotificationController::Notification(
                'Daily Production Planning',
                'Create',
                route('daily-production-planning.view', $dpp->id)
            );
        }
        return response()->json($dpp->id);
    }

    public function planning_store(Request $request){

        // Define validation rules
    $rules = [
        'scheduling.*.dpp_id' => 'required',
        'scheduling.*.planned_date' => 'required',
        'scheduling.*.op_name' => 'required',
        'scheduling.*.shift' => 'required',
        'scheduling.*.spec_break' => 'required',
        'scheduling.*.planned_qty' => 'required',
        'scheduling.*.machine' => 'required',
        'scheduling.*.tonnage' => 'required',
        'scheduling.*.cavity' => 'required',
    ];

    // Validate the request
    $validator = Validator::make($request->all(), $rules);
    if ($validator->fails()) {
        // Return a response with validation errors
        return response()->json(['errors' => $validator->errors()], 422);
    }
        $dppd_ids = [];
        // dd($request->scheduling);
        foreach ($request->scheduling as $scheduling) {
            $dppd = new DailyProductionPlanningDetail();
            $dppd->dpp_id = $scheduling['dpp_id'];
            $dppd->planned_date = $scheduling['planned_date'];
            $dppd->product_id = $scheduling['product_id'];
            $dppd->pro_order_no = $scheduling['pro_order_no'];
            $dppd->op_name = json_encode($scheduling['op_name']);
            $dppd->shift = $scheduling['shift'];
            $dppd->spec_break = $scheduling['spec_break'];
            $dppd->planned_qty = $scheduling['planned_qty'];
            $dppd->machine = $scheduling['machine'];
            $dppd->tonnage = $scheduling['tonnage'];
            $dppd->cavity = $scheduling['cavity'];
            ProductionOutputTraceabilityController::store($scheduling);
            $dppd->save();
            $dppd->save();
            $dppd_ids[] = $dppd->id;
        }


        return response()->json(['ids' => $dppd_ids, 'message' => 'success']);
    }

    public function view($id){
        if (!Auth::user()->hasPermissionTo('Daily Production Planning View')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $dpp = DailyProductionPlanning::find($id);
        $user = User::find($dpp->created_by);
        $fetch_orders = Order::with('order_detail.products.type_of_products','customers')->where('id', $dpp->order_id)->first();
        $dppds = DailyProductionPlanningDetail::where('dpp_id',$id)->get();
        $dppps = DailyProductionProduct::with('products.type_of_products','products.units')->where('dpp_id',$id)->get();
        $dpcps = DailyProductionChildPart::with('products.type_of_products','products.units','parent_products')->where('dpp_id',$id)->get();
        $products = Product::all();
        $users = User::all();
        $machines = Machine::all();
        $machine_tonnages = MachineTonnage::all();
        return view('mes.ppc.daily-production-planning.view', compact('dpp','dppds','dppps','dpcps','fetch_orders','user','products','users','machines','machine_tonnages'));
    }
    public function edit($dpp_id){
        if (!Auth::user()->hasPermissionTo('Daily Production Planning Edit')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $dpp = DailyProductionPlanning::find($dpp_id);
        $user = User::find($dpp->created_by);
        $fetch_orders = Order::with('order_detail.products.type_of_products','customers')->where('id', $dpp->order_id)->first();
        $dppds = DailyProductionPlanningDetail::where('dpp_id',$dpp_id)->get();
        $dppps = DailyProductionProduct::with('products.type_of_products','products.units')->where('dpp_id',$dpp_id)->get();
        $dpcps = DailyProductionChildPart::with('products.type_of_products','products.units','parent_products')->where('dpp_id',$dpp_id)->get();
        $products = Product::all();
        $users = User::all();
        $machines = Machine::all();
        $machine_tonnages = MachineTonnage::all();
        return view('mes.ppc.daily-production-planning.edit', compact('dpp','dppds','dppps','dpcps','fetch_orders','user','products','users','machines','machine_tonnages'));
    }
    public function update(){

    }
    public function destroy($id){
        if (!Auth::user()->hasPermissionTo('Daily Production Planning Delete')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $dpp = DailyProductionPlanning::find($id);
        $dppp = DailyProductionProduct::where('dpp_id', $id)->get();
        if ($dppp) {
            $dppp->each(function ($dp) {
                $dp->delete();
            });
        }
        $dpcp = DailyProductionChildPart::where('dpp_id', $id)->get();
        if ($dpcp) {
            $dpcp->each(function ($dcp) {
                $dcp->delete();
            });
        }
        $dppd = DailyProductionPlanningDetail::where('dpp_id', $id)->get();
        if ($dppd) {
            $dppd->each(function ($dpd) {
                $dpd->delete();
            });
        }
        $dpp->delete();
        return redirect()->route('daily-production-planning')->with('custom_success', 'Production Planning Deleted Successfully.');
    }

    public function getMachinesByTonnage($tonnageId)
    {
        $machines = Machine::where('tonnage_id', $tonnageId)->get();
        return response()->json($machines);
    }

}
