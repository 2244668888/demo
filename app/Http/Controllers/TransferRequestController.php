<?php

namespace App\Http\Controllers;

use App\Models\AreaLocation;
use App\Models\Department;
use App\Models\InitailNo;
use App\Models\Location;
use App\Models\Machine;
use App\Models\MaterialRequisition;
use App\Models\MaterialRequisitionDetails;
use App\Models\Product;
use App\Models\TransferRequest;
use App\Models\TransferRequestDetails;
use App\Models\TransferRequestIssue;
use App\Models\TransferRequestReceive;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransferRequestController extends Controller
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

            $query = TransferRequest::select('id', 'ref_no', 'request_date','request_from','request_to','machine', 'mrf_no','shift','status','rcv_by','rcv_date','rcv_time')->with(['mrf','department_from','department_to','user','machines']);


            // Apply search if a search term is provided
            if (!empty($search)) {
                $searchLower = strtolower($search);
                $query->where(function ($q) use ($searchLower) {
                    $q

                        ->where('ref_no', 'like', '%' . $searchLower . '%')
                        ->orWhereHas('mrf', function ($query) use ($searchLower) {
                            $query->where('ref_no', 'like', '%' . $searchLower . '%');
                        })
                        ->orWhere('request_date', 'like', '%' . $searchLower . '%')
                        ->orWhere('shift', 'like', '%' . $searchLower . '%')
                        ->orWhereHas('department_from', function ($query) use ($searchLower) {
                            $query->where('name', 'like', '%' . $searchLower . '%');
                        })
                        ->orWhereHas('machines', function ($query) use ($searchLower) {
                            $query->where('name', 'like', '%' . $searchLower . '%');
                        })
                        ->orWhereHas('department_to', function ($query) use ($searchLower) {
                            $query->where('name', 'like', '%' . $searchLower . '%');
                        })
                        ->orWhereHas('user', function ($query) use ($searchLower) {
                            $query->where('user_name', 'like', '%' . $searchLower . '%');
                        })
                        ->orWhere('rcv_date', 'like', '%' . $searchLower . '%')
                        ->orWhere('rcv_time', 'like', '%' . $searchLower . '%')
                        ->orWhere('status', 'like', '%' . $searchLower . '%');
                });
            }
            $results = null;

            if (!empty($columnsData)) {

                $sortableColumns = [
                    1 => 'ref_no',
                    2 => 'mrf_no',
                    3 => 'request_date',
                    4 => 'shift',
                    5 => 'request_from',
                    6 => 'machine',
                    7 => 'request_to',
                    8 => 'rcv_by',
                    9 => 'rcv_date',
                    10 => 'rcv_time',
                    11 => 'status',

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
                                $q->where('ref_no', 'like', '%' . $searchLower . '%');

                                break;
                            case 2:
                                $q->whereHas('mrf', function ($query) use ($searchLower) {
                                    $query->where('ref_no', 'like', '%' . $searchLower . '%');
                                });

                                break;
                                case 3:
                                    $searchLower = date('Y-m-d',strtotime($searchLower));
                                    $q->where('request_date', 'like', '%' . $searchLower . '%');


                                    break;
                                    case 4:

                                        $q->where('shift', 'like', '%' . $searchLower . '%');
                                        break;

                                        case 5:
                                            $q->whereHas('department_from', function ($query) use ($searchLower) {
                                                $query->where('name', 'like', '%' . $searchLower . '%');
                                            });
                                        break;
                                        case 6:
                                            $q->whereHas('machines', function ($query) use ($searchLower) {
                                                $query->where('name', 'like', '%' . $searchLower . '%');
                                            });

                                        break;
                                        case 7:
                                            $q->whereHas('department_to', function ($query) use ($searchLower) {
                                                $query->where('name', 'like', '%' . $searchLower . '%');
                                            });

                                        break;
                                        case 8:
                                            $q->whereHas('user', function ($query) use ($searchLower) {
                                                $query->where('user_name', 'like', '%' . $searchLower . '%');
                                            });
                                        break;
                                        case 9:
                                        $searchLower = date('Y-m-d',strtotime($searchLower));
                                        $q->where('rcv_date', 'like', '%' . $searchLower . '%');
                                        break;
                                        case 10:
                                        $q->where('rcv_time', 'like', '%' . $searchLower . '%');
                                        break;
                                        case 11:
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
                $row->request_date = date('d-m-Y',strtotime($row->request_date));
                $row->rcv_date = $row->rcv_date ? date('d-m-Y', strtotime($row->rcv_date)) : '';

                $row->action .= '<div class="d-flex"><a class="btn btn-success btn-sm mx-2" title="View" href="' . route('transfer_request.view', $row->id) .'"><i
                class="bi bi-eye"></i></a>';
                // $row->status = $status;
                if ($row->status == 'Requested'){
                $row->action .= '<a class="btn btn-info btn-sm mx-2" title="Edit" href="' .  route('transfer_request.edit', $row->id) .'"><i
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
                                                <form method="POST" action="' . route('transfer_request.destroy', $row->id) . '">
                                                    ' . csrf_field() . '
                                                    ' . method_field('DELETE') . '
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                    <a class="btn btn-primary btn-sm mx-2" title="Recieve" href="' .  route('transfer_request.receive', $row->id)  .'"><i
                                    class="bi bi-box-arrow-left"></i></a></div>';
                }

                if ($row->status == "Requested") {
                    $row->status = ' <span class="badge border border-dark text-dark">Requested</span>';
                }
                elseif ($row->status == 'Received') {
                    $row->status = '<span class="badge border border-primary text-primary">Received</span>';
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

            $query = TransferRequest::select('id', 'ref_no', 'request_date','request_from','request_to','machine','shift','status','rcv_by','rcv_date','rcv_time','mrf_no')->with(['mrf','department_from','department_to','user','machines']);


            // Apply search if a search term is provided
            if (!empty($search)) {
                $searchLower = strtolower($search);
                $query->where(function ($q) use ($searchLower) {
                    $q

                        ->where('ref_no', 'like', '%' . $searchLower . '%')
                        ->orWhereHas('mrf', function ($query) use ($searchLower) {
                            $query->where('ref_no', 'like', '%' . $searchLower . '%');
                        })
                        ->orWhere('request_date', 'like', '%' . $searchLower . '%')
                        ->orWhere('shift', 'like', '%' . $searchLower . '%')
                        ->orWhereHas('department_from', function ($query) use ($searchLower) {
                            $query->where('name', 'like', '%' . $searchLower . '%');
                        })
                        ->orWhereHas('machines', function ($query) use ($searchLower) {
                            $query->where('name', 'like', '%' . $searchLower . '%');
                        })
                        ->orWhereHas('department_to', function ($query) use ($searchLower) {
                            $query->where('name', 'like', '%' . $searchLower . '%');
                        })
                        ->orWhereHas('user', function ($query) use ($searchLower) {
                            $query->where('user_name', 'like', '%' . $searchLower . '%');
                        })
                        ->orWhere('rcv_date', 'like', '%' . $searchLower . '%')
                        ->orWhere('rcv_time', 'like', '%' . $searchLower . '%')
                        ->orWhere('status', 'like', '%' . $searchLower . '%');
                });
            }

            $sortableColumns = [
                1 => 'ref_no',
                2 => 'mrf_no',
                3 => 'request_date',
                4 => 'shift',
                5 => 'request_from',
                6 => 'machine',
                7 => 'request_to',
                8 => 'rcv_by',
                9 => 'rcv_date',
                10 => 'rcv_time',
                11 => 'status',

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
                $row->request_date = date('d-m-Y',strtotime($row->request_date));
                $row->rcv_date = $row->rcv_date ? date('d-m-Y', strtotime($row->rcv_date)) : '';


                $row->action .= '<div class="d-flex"><a class="btn btn-success btn-sm mx-2" title="View" href="' . route('transfer_request.view', $row->id) .'"><i
                class="bi bi-eye"></i></a>';
                // $row->status = $status;
                if ($row->status == 'Requested'){
                $row->action .= '<a class="btn btn-info btn-sm mx-2" title="Edit" href="' .  route('transfer_request.edit', $row->id) .'"><i
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
                                                <form method="POST" action="' . route('transfer_request.destroy', $row->id) . '">
                                                    ' . csrf_field() . '
                                                    ' . method_field('DELETE') . '
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <a class="btn btn-primary btn-sm mx-2" title="Recieve" href="' .  route('transfer_request.receive', $row->id)  .'"><i
                                    class="bi bi-box-arrow-left"></i></a></div>';
                }
                if ($row->status == "Requested") {
                    $row->status = ' <span class="badge border border-dark text-dark">Requested</span>';
                } elseif ($row->status == 'Issued') {
                    $row->status = '<span class="badge border border-warning text-warning">Issued</span>';
                }
                elseif ($row->status == 'Received') {
                    $row->status = '<span class="badge border border-primary text-primary">Received</span>';
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
    public function index(){
        if (!Auth::user()->hasPermissionTo('Transfer Request Create') ||
            !Auth::user()->hasPermissionTo('Transfer Request View')||
            !Auth::user()->hasPermissionTo('Transfer Request List')||
            !Auth::user()->hasPermissionTo('Transfer Request Edit')||
            !Auth::user()->hasPermissionTo('Transfer Request Delete')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $transfer_requests = TransferRequest::with('mrf')->get();
        $depts = Department::all();
        $machines = Machine::all();
        $users = User::all();
        return view('wms.operations.transfer-request.index',compact('transfer_requests','depts','machines','users'));
    }

    public function create(){
        if (!Auth::user()->hasPermissionTo('Transfer Request Create')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $setting = InitailNo::where('screen', 'Transfer Request')->first();
        $year = Carbon::now('Asia/Kuala_Lumpur')->format('y');
        if ($setting) {
            $tr_no = TransferRequest::orderBy('id', 'DESC')->first();
            if ($tr_no) {
                // Extract running_no from $tr_no->tr_no_no which is in format 'dpp/ref/running_no/year'
                $parts = explode('/', $tr_no->ref_no);
                if (count($parts) == 3) {
                    $running_no = (int) $parts[1] + 1;
                } else {
                    $running_no = 1; // Fallback in case the format is unexpected
                }
                $tr_no_no = $setting->ref_no . '/' . $running_no . '/' . $year;
            } else {
                $tr_no_no = $setting->ref_no . '/' . $setting->running_no . '/' . $year;
            }
        } else {
            $tr_no = TransferRequest::orderBy('id', 'DESC')->first();
            if ($tr_no) {
                // Extract running_no from $tr_no->ref_no which is in format 'tr/ref/running_no/year'
                $parts = explode('/', $tr_no->ref_no);
                if (count($parts) == 3) {
                    $running_no = (int) $parts[1] + 1;
                } else {
                    $running_no = 1; // Fallback in case the format is unexpected
                }
                $tr_no_no = 'TR/' . $running_no . '/' . $year;
            } else {
                $tr_no_no = 'TR/1/' . $year;
            }
        }
        $material_requisitions = MaterialRequisition::with('machines')->get();
        $depts = Department::all();
        $products = Product::all();
        return view('wms.operations.transfer-request.create',compact('tr_no_no','material_requisitions','products','depts'));

    }

    public function store(Request $request){
        if (!Auth::user()->hasPermissionTo('Transfer Request Create')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $request->validate([
            'request_date'=>'required',
            'request_from'=>'required',
            'request_to'=>'required',
            'product'=>'required'
        ]);
        $tr = new TransferRequest();
        $tr->ref_no = $request->ref_no;
        $tr->request_date = $request->request_date;
        $tr->request_from = $request->request_from;
        $tr->request_to = $request->request_to;
        $tr->description = $request->description;
        $tr->mrf_no = $request->mrf_no ?? '';
        $tr->shift = $request->shift ?? '';
        $tr->machine = $request->machine_id ?? '';
        $tr->save();
        $tr_id = $tr->id;
        foreach ($request->product as $key => $product) {
            $trd = new TransferRequestDetails();
            $trd->transfer_request_id = $tr_id;
            $trd->product_id = $product['product_id'];
            $trd->request_qty = $product['req_qty'];
            $trd->request_remarks = $product['remarks'];
            $trd->save();
        }

        NotificationController::Notification(
            'Transfer Request',
            'Create', '' .
            route('transfer_request.view', $tr->id)
        );
        return redirect()->route('transfer_request.index')->with('custom_success', 'Transfer Request Created Successfully');

    }

    public function edit($id){
        if (!Auth::user()->hasPermissionTo('Transfer Request Edit')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $tr = TransferRequest::find($id);
        $trds = TransferRequestDetails::where('transfer_request_id',$id)->get();
        $material_requisitions = MaterialRequisition::with('machines')->get();
        $depts = Department::all();
        $products = Product::all();
        $machines = Machine::all();
        return view('wms.operations.transfer-request.edit',compact('tr','trds','material_requisitions','depts','products','machines'));
    }

    public function update(Request $request, $id){
        if (!Auth::user()->hasPermissionTo('Transfer Request Edit')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $request->validate([
            'request_date'=>'required',
            'request_from'=>'required',
            'request_to'=>'required',
            'product'=>'required'
        ]);
        $tr = TransferRequest::find($id);
        $tr->ref_no = $request->ref_no;
        $tr->request_date = $request->request_date;
        $tr->request_from = $request->request_from;
        $tr->request_to = $request->request_to;
        $tr->description = $request->description;
        $tr->mrf_no = $request->mrf_no ?? '';
        $tr->shift = $request->shift ?? '';
        $tr->machine = $request->machine_id ?? '';
        $tr->save();
        $tr_id = $tr->id;
        TransferRequestDetails::where('transfer_request_id',$tr_id)->delete();
        foreach ($request->product as $key => $product) {
            $trd = new TransferRequestDetails();
            $trd->transfer_request_id = $tr_id;
            $trd->product_id = $product['product_id'];
            $trd->request_qty = $product['req_qty'];
            $trd->request_remarks = $product['remarks'];
            $trd->save();
        }
        return redirect()->route('transfer_request.index')->with('custom_success', 'Transfer Request Updated Successfully');

    }

    public function issue($id){
        if (!Auth::user()->hasPermissionTo('Transfer Request Issue')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $tr = TransferRequest::find($id);
        $trds = TransferRequestDetails::where('transfer_request_id',$id)->get();
        $material_requisitions = MaterialRequisition::with('machines')->get();
        $productIds = $trds->pluck('product_id');
        $tr_detail_ids = $trds->pluck('id');
        $locations = Location::whereIn('product_id',$productIds)->with('area','rack','level','product')->get();
        $depts = Department::all();
        $products = Product::all();
        $machines = Machine::all();
        $tr_issue = TransferRequestIssue::whereIn('tr_detail_id',$tr_detail_ids)->get();
        return view('wms.operations.transfer-request.issue',compact('tr','trds','material_requisitions','depts','products','machines','locations','tr_issue'));

    }

    public function issued(Request $request, $id){
        $tr = TransferRequest::find($id);
        $tr->issue_by = $request->issue_by;
        $tr->issue_date = $request->issue_date;
        $tr->issue_remarks = $request->issue_remarks;;
        $tr->issue_time = $request->issue_time;;
        $flag = true;

        foreach ($request->product as $key => $product) {
            $trd = TransferRequestDetails::find($product['trd_id']);
            $trd->issue_qty = $product['issue_qty'];
            if ($product['issue_qty'] < $trd->request_qty) {
                $flag = false;
            }
            $trd->issue_remarks = $product['issue_remarks'];
            $trd->save();

            $prv_tr_issue = TransferRequestIssue::where('tr_detail_id',$product['trd_id'])->get();
            if($prv_tr_issue){
                foreach ($prv_tr_issue as $prv_issue){
                    $location = Location::where('area_id',$prv_issue->area)
                                ->where('rack_id', $prv_issue->rack)
                                ->where('level_id', $prv_issue->level)
                                ->where('lot_no', $prv_issue->lot_no)
                                ->first();
                    if ($location) {
                        // Example operation: reducing the location's quantity by the requisitioned quantity
                        $location->used_qty += $prv_issue->qty;
                        $location->save();
                    }
                }
                TransferRequestIssue::where('tr_detail_id', $product['trd_id'])->delete();
            }

        }

        if($flag){
            $tr->status = 'Issued';
        }
        $tr->save();

        $storedData = json_decode($request->input('details'), true);

        $newArray = collect($storedData)->flatMap(function ($subArray) {
            return $subArray;
        })->sortBy('hiddenId')->values()->toArray();



        foreach ($newArray as $key => $tr_issue) {
            $tr_issue_table = new TransferRequestIssue();
            $tr_issue_table->area = $tr_issue['area'];
            $tr_issue_table->rack = $tr_issue['rack'];
            $tr_issue_table->level = $tr_issue['level'];
            $tr_issue_table->lot_no = $tr_issue['lot_no'];
            $tr_issue_table->qty = $tr_issue['qty'];
            $tr_issue_table->tr_detail_id = $tr_issue['trd_id'];
            $tr_issue_table->product_id = $tr_issue['hiddenId'];
            $tr_issue_table->save();

            $location = Location::where('area_id',$tr_issue['area'])
                                ->where('rack_id',$tr_issue['rack'])
                                ->where('level_id',$tr_issue['level'])
                                ->where('lot_no',$tr_issue['lot_no'])
                                ->first();
            if ($location) {
                // Example operation: reducing the location's quantity by the requisitioned quantity
                $location->used_qty -= $tr_issue['qty'];
            }
            $location->save();

        }
        return redirect()->route('transfer_request.index')->with('custom_success', 'Transfer Request Issued');
    }

    public function receive($id){
        if (!Auth::user()->hasPermissionTo('Transfer Request Receive')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $depts = Department::all();
        $machines = Machine::all();
        $products = Product::with('type_of_products','units')->get();
        $tr = TransferRequest::find($id);
        $user = User::find($tr->issue_by);
        $trds = TransferRequestDetails::where('transfer_request_id',$id)->get();
        $tr_detail_ids = $trds->pluck('id');
        $locations = AreaLocation::with('area','rack','level')->get();
        $productIds = $trds->pluck('product_id');
        $locations = Location::with('area','rack','level','product')->get();
        $tr_receive = TransferRequestReceive::whereIn('tr_detail_id',$tr_detail_ids)->get();
        $material_requisitions = MaterialRequisition::with('machines')->get();



        return view('wms.operations.transfer-request.receive', compact('user','depts','machines','products','tr','trds','locations','tr_receive','material_requisitions'));
    }

    public function received(Request $request, $id){
// dd($request->all());
        $tr = TransferRequest::find($id);
        $tr->rcv_by = $request->rcv_by;
        $tr->rcv_date = $request->rcv_date;
        $tr->rcv_remarks = $request->rcv_remarks;;
        $tr->rcv_time = $request->rcv_time;;
        $flag = true;

        foreach ($request->product as $key => $product) {
            $trd = TransferRequestDetails::find($product['tr_detail_id']);
            $trd->rcv_qty = $product['rcv_qty'];
            if ($product['rcv_qty'] < $trd->request_qty) {
                $discrepancy['product_id'] = $trd->product_id;
                $discrepancy['mrf_tr_id'] = $id;
                $discrepancy['order_no'] = '';
                $discrepancy['issue_qty'] = $trd->request_qty;
                $discrepancy['rcv_qty'] = $product['rcv_qty'];
                $discrepancy['date'] = date('Y-m-d');
                DiscrepancyController::create($discrepancy);
            }
            $trd->rcv_remarks = $product['rcv_remarks'];
            $trd->save();

            $prv_tr_rcv = TransferRequestReceive::where('tr_detail_id',$product['tr_detail_id'])->get();
            if($prv_tr_rcv){
                foreach ($prv_tr_rcv as $tr_rcv){
                    $location = Location::where('area_id', $tr_rcv->area)
                                ->where('rack_id', $tr_rcv->rack)
                                ->where('level_id', $tr_rcv->level)
                                ->where('lot_no', $tr_rcv->lot_no)
                                ->first();
                    if ($location) {
                        // Example operation: reducing the location's quantity by the requisitioned quantity
                        $location->used_qty -= $tr_rcv->qty;
                        $location->save();
                    }
                }
                TransferRequestReceive::where('tr_detail_id', $product['tr_detail_id'])->delete();
            }

        }

        $tr->status = 'Received';
        $tr->save();

        $storedData = json_decode($request->input('details'), true);

        $newArray = collect($storedData)->flatMap(function ($subArray) {
            return $subArray;
        })->sortBy('hiddenId')->values()->toArray();



        foreach ($newArray as $key => $product_rcv) {
            $tr_rcv = new TransferRequestReceive();
            $tr_rcv->area = $product_rcv['area'];
            $tr_rcv->rack = $product_rcv['rack'];
            $tr_rcv->level = $product_rcv['level'];
            $tr_rcv->lot_no = $product_rcv['lot_no'] ?? '';
            $tr_rcv->qty = $product_rcv['qty'];
            $tr_rcv->tr_detail_id = $product_rcv['tr_detail_id'];
            $tr_rcv->product_id = $product_rcv['hiddenId'];
            $tr_rcv->save();

            $location = Location::where('area_id',$product_rcv['area'])
                                ->where('rack_id',$product_rcv['rack'])
                                ->where('level_id',$product_rcv['level']);
            if($product_rcv['lot_no']){
                $location->where('lot_no',$product_rcv['lot_no']);
            }else{
                $location->where('lot_no','');
            }
            $location = $location->first();
            if ($location) {
                // Example operation: reducing the location's quantity by the requisitioned quantity
                $location->used_qty += $product_rcv['qty'];
                $location->save();
            } else {
                $new_location = new Location();
                $new_location->area_id = $product_rcv['area'];
                $new_location->rack_id = $product_rcv['rack'];
                $new_location->level_id = $product_rcv['level'];
                $new_location->used_qty = $product_rcv['qty'];
                $new_location->product_id = $product_rcv['hiddenId'];
                $new_location->save();
            }
        }

        NotificationController::Notification(
            'Transfer Request',
            'Receive',
            route('transfer_request.view', $tr->id)
        );

        return redirect()->route('transfer_request.index')->with('success', 'Transfer Request Recevied');
    }

    public function destroy($id) {
        if (!Auth::user()->hasPermissionTo('Transfer Request Delete')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }

        try {
            DB::beginTransaction();

            $trd = TransferRequestDetails::where('transfer_request_id', $id)->get();
            $tr_detail_ids = $trd->pluck('id')->toArray(); // Convert to array

            TransferRequestReceive::whereIn('tr_detail_id', $tr_detail_ids)->delete();
            TransferRequestIssue::whereIn('tr_detail_id', $tr_detail_ids)->delete();

            TransferRequestDetails::whereIn('id', $tr_detail_ids)->delete(); // Delete all details in one query

            TransferRequest::where('id', $id)->delete();

            DB::commit();

            return redirect()->route('transfer_request.index')->with('success', 'Transfer Request Deleted');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('custom_errors', 'An error occurred while deleting the Transfer Request');
        }
    }

    public function view($id){
        if (!Auth::user()->hasPermissionTo('Transfer Request View')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $depts = Department::all();
        $machines = Machine::all();
        $products = Product::with('type_of_products','units')->get();
        $tr = TransferRequest::find($id);
        $user = User::find($tr->issue_by);
        $user2 = User::find($tr->rcv_by);
        $trds = TransferRequestDetails::where('transfer_request_id',$id)->get();
        $tr_detail_ids = $trds->pluck('id');
        $locations = AreaLocation::with('area','rack','level')->get();
        $tr_receive = TransferRequestReceive::whereIn('tr_detail_id',$tr_detail_ids)->get();
        $material_requisitions = MaterialRequisition::with('machines')->get();
        return view('wms.operations.transfer-request.view', compact('user','user2','depts','machines','products','tr','trds','locations','tr_receive','material_requisitions'));
    }

    public function getProductsByMrf($mrf_id)
    {
        // Fetch the material requisition
        $material_requisition = MaterialRequisition::findOrFail($mrf_id);

        // Fetch related products from material_requisition_details
        $products = MaterialRequisitionDetails::where('material_requisition_id', $mrf_id)
                    ->with('product.units', 'product.type_of_products') // Assuming relations exist
                    ->get()
                    ->map(function ($detail) {
                        return [
                            'id' => $detail->product_id,
                            'part_no' => $detail->product->part_no,
                            'part_name' => $detail->product->part_name,
                            'unit_name' => $detail->product->units->name,
                            'model' => $detail->product->model,
                            'variance' => $detail->product->variance,
                            'type' => $detail->product->type_of_products->type ?? '',
                        ];
                    });

        // Return as JSON response
        return response()->json([
            'products' => $products
        ]);
    }

}
