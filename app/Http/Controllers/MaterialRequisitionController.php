<?php

namespace App\Http\Controllers;

use App\Models\AreaLocation;
use App\Models\Customer;
use App\Models\DailyProductionPlanningDetail;
use App\Models\Department;
use App\Models\InitailNo;
use App\Models\Location;
use App\Models\Machine;
use App\Models\MaterialPlanning;
use App\Models\MaterialPlanningDetail;
use App\Models\MaterialRequisition;
use App\Models\MaterialRequisitionDetails;
use App\Models\MaterialRequisitionProductDetails;
use App\Models\MaterialRequisitionProductDetailsReceive;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;

class MaterialRequisitionController extends Controller
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

            $query = MaterialRequisition::select('id', 'ref_no', 'request_date', 'pro_order_no', 'request_from', 'request_to', 'plan_date', 'machine', 'shift', 'status')->with(['machines', 'department_from', 'department_to']);


            // Apply search if a search term is provided
            if (!empty($search)) {
                $searchLower = strtolower($search);
                $query->where(function ($q) use ($searchLower) {
                    $q

                        ->where('ref_no', 'like', '%' . $searchLower . '%')
                        ->orWhere('pro_order_no', 'like', '%' . $searchLower . '%')
                        ->orWhereHas('department_from', function ($query) use ($searchLower) {
                            $query->where('name', 'like', '%' . $searchLower . '%');
                        })
                        ->orWhereHas('department_to', function ($query) use ($searchLower) {
                            $query->where('name', 'like', '%' . $searchLower . '%');
                        })
                        ->orWhere('request_date', 'like', '%' . $searchLower . '%')
                        ->orWhere('plan_date', 'like', '%' . $searchLower . '%')
                        ->orWhereHas('machines', function ($query) use ($searchLower) {
                            $query->where('name', 'like', '%' . $searchLower . '%');
                        })
                        ->orWhere('shift', 'like', '%' . $searchLower . '%')
                        ->orWhere('status', 'like', '%' . $searchLower . '%');
                });
            }
            $results = null;

            if (!empty($columnsData)) {

                $sortableColumns = [
                    1 => 'ref_no',
                    2 => 'pro_order_no',
                    3 => 'request_from',
                    4 => 'request_to',
                    5 => 'request_date',
                    6 => 'plan_date',
                    7 => 'machine',
                    8 => 'shift',
                    9 => 'status',

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
                                $q->where('ref_no', 'like', '%' . $searchLower . '%');

                                break;
                            case 2:
                                $q->where('pro_order_no', 'like', '%' . $searchLower . '%');

                                break;
                            case 3:
                                $q->whereHas('department_from', function ($query) use ($searchLower) {
                                    $query->where('name', 'like', '%' . $searchLower . '%');
                                });
                                break;
                            case 4:
                                $q->whereHas('department_to', function ($query) use ($searchLower) {
                                    $query->where('name', 'like', '%' . $searchLower . '%');
                                });
                                break;

                            case 5:
                                $q->where('request_date', 'like', '%' . $searchLower . '%');
                                break;
                            case 6:
                                $q->where('plan_date', 'like', '%' . $searchLower . '%');
                                break;
                            case 7:
                                $q->whereHas('machines', function ($query) use ($searchLower) {
                                    $query->where('name', 'like', '%' . $searchLower . '%');
                                });
                                break;
                            case 8:
                                $q->where('shift', 'like', '%' . $searchLower . '%');
                                break;
                            case 9:
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
                $row->request_date = date('d-m-Y', strtotime($row->request_date));
                $row->plan_date = date('d-m-Y', strtotime($row->plan_date));
                $row->sr_no = $start + $index + 1;

                $row->action .= '<div class="d-flex"><a class="btn btn-success btn-sm mx-2" title="View" href="' . route('material_requisition.view', $row->id) . '"><i
                                        class="bi bi-eye"></i></a>';
                // $row->status = $status;
                if ($row->status == 'Requested') {
                    $row->action .= '<a class="btn btn-info btn-sm" title="Edit" href="' .  route('material_requisition.edit', $row->id) . '"><i
                                            class="bi bi-pencil"></i></a>
                                        <a class="btn btn-warning btn-sm mx-2" title="Issue" href="' .  route('material_requisition.issue', $row->id) . '"><i
                                            class="bi bi-box-arrow-right"></i></a>
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
                                                <form method="POST" action="' . route('material_requisition.destroy', $row->id) . '">
                                                    ' . csrf_field() . '
                                                    ' . method_field('DELETE') . '
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>';
                } elseif ($row->status == 'Issued') {
                    $row->action .= '<a class="btn btn-primary btn-sm" title="Recieve" href="' .  route('material_requisition.receive', $row->id)  . '"><i
                                            class="bi bi-box-arrow-left"></i></a></div>';
                }

                if ($row->status == "Requested") {
                    $row->status = ' <span class="badge border border-dark text-dark">Requested</span>';
                } elseif ($row->status == 'Issued') {
                    $row->status = '<span class="badge border border-warning text-warning">Issued</span>';
                } elseif ($row->status == 'Received') {
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

            $query = MaterialRequisition::select('id', 'ref_no', 'request_date', 'pro_order_no', 'request_from', 'request_to', 'plan_date', 'machine', 'shift', 'status')->with(['machines', 'department_from', 'department_to']);


            // Apply search if a search term is provided
            if (!empty($search)) {
                $searchLower = strtolower($search);
                $query->where(function ($q) use ($searchLower) {
                    $q

                        ->where('ref_no', 'like', '%' . $searchLower . '%')
                        ->orWhere('pro_order_no', 'like', '%' . $searchLower . '%')
                        ->orWhereHas('department_from', function ($query) use ($searchLower) {
                            $query->where('name', 'like', '%' . $searchLower . '%');
                        })
                        ->orWhereHas('department_to', function ($query) use ($searchLower) {
                            $query->where('name', 'like', '%' . $searchLower . '%');
                        })
                        ->orWhere('request_date', 'like', '%' . $searchLower . '%')
                        ->orWhere('plan_date', 'like', '%' . $searchLower . '%')
                        ->orWhereHas('machines', function ($query) use ($searchLower) {
                            $query->where('name', 'like', '%' . $searchLower . '%');
                        })
                        ->orWhere('shift', 'like', '%' . $searchLower . '%')
                        ->orWhere('status', 'like', '%' . $searchLower . '%');
                });
            }
            $sortableColumns = [
                1 => 'ref_no',
                2 => 'pro_order_no',
                3 => 'request_from',
                4 => 'request_to',
                5 => 'request_date',
                6 => 'plan_date',
                7 => 'machine',
                8 => 'shift',
                9 => 'status',

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
                $row->request_date = date('d-m-Y', strtotime($row->request_date));
                $row->plan_date = date('d-m-Y', strtotime($row->plan_date));
                $row->sr_no = $start + $index + 1;

                $row->action .= '<div class="d-flex"><a class="btn btn-success btn-sm mx-2" title="View" href="' . route('material_requisition.view', $row->id) . '"><i
                                        class="bi bi-eye"></i></a>';
                // $row->status = $status;
                if ($row->status == 'Requested') {
                    $row->action .= '<a class="btn btn-info btn-sm" title="Edit" href="' .  route('material_requisition.edit', $row->id) . '"><i
                                            class="bi bi-pencil"></i></a>
                                        <a class="btn btn-warning btn-sm mx-2" title="Issue" href="' .  route('material_requisition.issue', $row->id) . '"><i
                                            class="bi bi-box-arrow-right"></i></a>
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
                                                <form method="POST" action="' . route('material_requisition.destroy', $row->id) . '">
                                                    ' . csrf_field() . '
                                                    ' . method_field('DELETE') . '
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>';
                } elseif ($row->status == 'Issued') {
                    $row->action .= '<a class="btn btn-primary btn-sm" title="Recieve" href="' .  route('material_requisition.receive', $row->id)  . '"><i
                                            class="bi bi-box-arrow-left"></i></a></div>';
                }

                if ($row->status == "Requested") {
                    $row->status = ' <span class="badge border border-dark text-dark">Requested</span>';
                } elseif ($row->status == 'Issued') {
                    $row->status = '<span class="badge border border-warning text-warning">Issued</span>';
                } elseif ($row->status == 'Received') {
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
    public function index()
    {
        if (
            !Auth::user()->hasPermissionTo('Material Requisition Create') ||
            !Auth::user()->hasPermissionTo('Material Requisition View') ||
            !Auth::user()->hasPermissionTo('Material Requisition List') ||
            !Auth::user()->hasPermissionTo('Material Requisition Edit') ||
            !Auth::user()->hasPermissionTo('Material Requisition Delete')
        ) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $depts = Department::all();
        $machines = Machine::all();
        $material_requisitions = MaterialRequisition::all();
        return view('wms.operations.material-requisition.index', compact('material_requisitions', 'depts', 'machines'));
    }

    public function create($id = '')
    {
        if (!Auth::user()->hasPermissionTo('Material Requisition Create')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $production_orders = DailyProductionPlanningDetail::all();
        $depts = Department::all();
        $machines = Machine::all();
        $products = Product::with('type_of_products', 'units')->get();
        $setting = InitailNo::where('screen', 'Material Requisition')->first();
        $year = Carbon::now('Asia/Kuala_Lumpur')->format('y');
        if ($setting) {
            $mrf_no = MaterialRequisition::orderBy('id', 'DESC')->first();
            if ($mrf_no) {
                // Extract running_no from $mrf_no->mrf_no_no which is in format 'dpp/ref/running_no/year'
                $parts = explode('/', $mrf_no->ref_no);
                if (count($parts) == 3) {
                    $running_no = (int) $parts[1] + 1;
                } else {
                    $running_no = 1; // Fallback in case the format is unexpected
                }
                $mrf_no_no = $setting->ref_no . '/' . $running_no . '/' . $year;
            } else {
                $mrf_no_no = $setting->ref_no . '/' . $setting->running_no . '/' . $year;
            }
        } else {
            $mrf_no = MaterialRequisition::orderBy('id', 'DESC')->first();
            if ($mrf_no) {
                // Extract running_no from $mrf_no->ref_no which is in format 'dpp/ref/running_no/year'
                $parts = explode('/', $mrf_no->ref_no);
                if (count($parts) == 3) {
                    $running_no = (int) $parts[1] + 1;
                } else {
                    $running_no = 1; // Fallback in case the format is unexpected
                }
                $mrf_no_no = 'MRF/' . $running_no . '/' . $year;
            } else {
                $mrf_no_no = 'MRF/1/' . $year;
            }
        }
        return view('wms.operations.material-requisition.create', compact('production_orders', 'depts', 'machines', 'products', 'mrf_no_no', 'id'));
    }

    public function create_planning($id = '')
    {
        $production_orders = DailyProductionPlanningDetail::with('machines')->get();
        $depts = Department::select('id', 'name')->get();
        $setting = InitailNo::where('screen', 'Material Requisition')->first();
        $year = Carbon::now('Asia/Kuala_Lumpur')->format('y');
        if ($setting) {
            $mrf_no = MaterialRequisition::orderBy('id', 'DESC')->first();
            if ($mrf_no) {
                // Extract running_no from $mrf_no->mrf_no_no which is in format 'dpp/ref/running_no/year'
                $parts = explode('/', $mrf_no->ref_no);
                if (count($parts) == 3) {
                    $running_no = (int) $parts[1] + 1;
                } else {
                    $running_no = 1; // Fallback in case the format is unexpected
                }
                $mrf_no_no = $setting->ref_no . '/' . $running_no . '/' . $year;
            } else {
                $mrf_no_no = $setting->ref_no . '/' . $setting->running_no . '/' . $year;
            }
        } else {
            $mrf_no = MaterialRequisition::orderBy('id', 'DESC')->first();
            if ($mrf_no) {
                // Extract running_no from $mrf_no->ref_no which is in format 'dpp/ref/running_no/year'
                $parts = explode('/', $mrf_no->ref_no);
                if (count($parts) == 3) {
                    $running_no = (int) $parts[1] + 1;
                } else {
                    $running_no = 1; // Fallback in case the format is unexpected
                }
                $mrf_no_no = 'MRF/' . $year . '/' . $running_no;
            } else {
                $mrf_no_no = 'MRF/1/' . $year;
            }
        }
        return view('wms.operations.material-planning.create', compact('production_orders', 'depts', 'mrf_no_no', 'id'));
    }

    public function store_materialplanning(Request $request)
    {
        if (!Auth::user()->hasPermissionTo('Material Requisition Create')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $validated = $request->validate([
            'pro_order_no1' => 'required',
            'request_date' => 'required',
            'request_from' => 'required',
            'plan_date' => 'required'
        ], [
            'pro_order_no1.required' => 'Production order no is required.',
        ]);

        $formattedPlanDate = $request->plan_date;
        $material_planning = new MaterialPlanning();
        $material_planning->dppd_id = $request->pro_order_no1;
        $material_planning->ref_no = $request->ref_no;
        $material_planning->request_date = $request->request_date;
        $material_planning->plan_date = $formattedPlanDate;
        $material_planning->department_id = $request->request_from;
        $material_planning->remarks = $request->remarks;
        $material_planning->total_planned_qty = $request->total_planned_qty;
        $material_planning->save();

        if ($request->products) {
            foreach ($request->products as $products) {
                $detail = new MaterialPlanningDetail();
                $detail->planning_id = $material_planning->id;
                $detail->product_id = $products['product_id'] ?? null;
                $detail->required_qty = $products['required_qty'] ?? null;
                $detail->inventory_qty = $products['inventory_qty'] ?? null;
                $detail->request_qty = $products['request_qty'] ?? null;
                $detail->difference = $products['difference'] ?? null;
                $detail->save();
            }
        }

        return redirect()->route('daily-production-planning')->with('custom_success', 'Material Planning Created Successfully.');
    }

    public function store(Request $request)
    {
        if (!Auth::user()->hasPermissionTo('Material Requisition Create')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $validated = $request->validate([
            'pro_order_no' => 'required',
            'ref_no' => 'required',
            'request_date' => 'required',
            'plan_date' => 'required',
            'request_from' => 'required',
            'transfer_to' => 'required',
            'shift' => 'required',
            'machine' => 'required',
            'product' => 'required'
        ]);
        $material_requisition = new MaterialRequisition();
        $material_requisition->pro_order_no = $request->pro_order_no;
        $material_requisition->ref_no = $request->ref_no;
        $material_requisition->request_date = $request->request_date;
        $material_requisition->plan_date = $request->plan_date;
        $material_requisition->request_from = $request->request_from;
        $material_requisition->request_to = $request->transfer_to;
        $material_requisition->shift = $request->shift;
        $material_requisition->description = $request->description;
        $material_requisition->machine = $request->machine;
        $material_requisition->save();
        $id = $material_requisition->id;

        if ($request->product) {
            foreach ($request->product as $product) {
                $material_requisition_product = new MaterialRequisitionDetails();
                $material_requisition_product->material_requisition_id = $id;
                $material_requisition_product->product_id = $product['product_id'];
                $material_requisition_product->request_qty = $product['req_qty'];
                $material_requisition_product->remarks = $product['remarks'];
                $material_requisition_product->save();
            }
        }

        NotificationController::Notification(
            'Material Requisition',
            'Create',
            route('material_requisition.view', $material_requisition->id)
        );
        return redirect()->route('material_requisition.index')->with('custom_success', 'Material Requisition Created Successfully.');
    }

    public function edit($id)
    {
        if (!Auth::user()->hasPermissionTo('Material Requisition Edit')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $production_orders = DailyProductionPlanningDetail::all();
        $depts = Department::all();
        $machines = Machine::all();
        $products = Product::with('type_of_products', 'units')->get();
        $material_requisition = MaterialRequisition::find($id);
        $material_requisition_details = MaterialRequisitionDetails::where('material_requisition_id', $id)->get();
        return view('wms.operations.material-requisition.edit', compact('production_orders', 'depts', 'machines', 'products', 'material_requisition', 'material_requisition_details'));
    }
    public function update(Request $request, $id)
    {
        if (!Auth::user()->hasPermissionTo('Material Requisition Edit')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $validated = $request->validate([
            'pro_order_no' => 'required',
            'ref_no' => 'required',
            'request_date' => 'required',
            'plan_date' => 'required',
            'request_from' => 'required',
            'transfer_to' => 'required',
            'shift' => 'required',
            'machine' => 'required',
            'product' => 'required'
        ]);
        $material_requisition = MaterialRequisition::find($id);
        $material_requisition->pro_order_no = $request->pro_order_no;
        $material_requisition->ref_no = $request->ref_no;
        $material_requisition->request_date = $request->request_date;
        $material_requisition->plan_date = $request->plan_date;
        $material_requisition->request_from = $request->request_from;
        $material_requisition->request_to = $request->transfer_to;
        $material_requisition->shift = $request->shift;
        $material_requisition->description = $request->description;
        $material_requisition->machine = $request->machine;
        $material_requisition->save();
        MaterialRequisitionDetails::where('material_requisition_id', $id)->delete();
        if ($request->product) {
            foreach ($request->product as $product) {
                $material_requisition_product = new MaterialRequisitionDetails();
                $material_requisition_product->material_requisition_id = $id;
                $material_requisition_product->product_id = $product['product_id'];
                $material_requisition_product->request_qty = $product['req_qty'];
                $material_requisition_product->remarks = $product['remarks'];
                $material_requisition_product->save();
            }
        }
        return redirect()->route('material_requisition.index')->with('custom_success', 'Material Requisition Updated Successfully.');
    }

    public function view($id)
    {
        if (!Auth::user()->hasPermissionTo('Material Requisition View')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $production_orders = DailyProductionPlanningDetail::all();
        $depts = Department::all();
        $machines = Machine::all();
        $products = Product::with('type_of_products', 'units')->get();
        $material_requisition = MaterialRequisition::find($id);
        $user = User::find($material_requisition->issue_by);
        $material_requisition_details = MaterialRequisitionDetails::where('material_requisition_id', $id)->get();
        $productIds = $material_requisition_details->pluck('product_id');
        $mrf_detail_ids = $material_requisition_details->pluck('id');
        $locations = AreaLocation::with('area', 'rack', 'level')->get();
        $material_requisition_product_details = MaterialRequisitionProductDetailsReceive::whereIn('mrf_detail_id', $mrf_detail_ids)->get();


        return view('wms.operations.material-requisition.view', compact('user', 'production_orders', 'depts', 'machines', 'products', 'material_requisition', 'material_requisition_details', 'locations', 'material_requisition_product_details'));
    }

    public function destroy($id)
    {
        if (!Auth::user()->hasPermissionTo('Material Requisition Delete')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        MaterialRequisitionDetails::where('material_requisition_id', $id)->delete();
        MaterialRequisition::where('id', $id)->delete();
        return redirect()->route('material_requisition.index')->with('custom_success', 'Material Requisition Deleted Successfully.');
    }

    public function issue($id)
    {
        if (!Auth::user()->hasPermissionTo('Material Requisition Issue')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $production_orders = DailyProductionPlanningDetail::all();
        $depts = Department::all();
        $machines = Machine::all();
        $products = Product::with('type_of_products', 'units')->get();
        $material_requisition = MaterialRequisition::find($id);
        $material_requisition_details = MaterialRequisitionDetails::where('material_requisition_id', $id)->get();
        $productIds = $material_requisition_details->pluck('product_id');
        $mrf_detail_ids = $material_requisition_details->pluck('id');
        $locations = Location::whereIn('product_id', $productIds)->with('area', 'rack', 'level', 'product')->get();
        $material_requisition_product_details = MaterialRequisitionProductDetails::whereIn('mrf_detail_id', $mrf_detail_ids)->get();


        return view('wms.operations.material-requisition.issue', compact('production_orders', 'depts', 'machines', 'products', 'material_requisition', 'material_requisition_details', 'locations', 'material_requisition_product_details'));
    }

    public function issued(Request $request, $id)
    {

        $material_requisition = MaterialRequisition::find($id);
        $material_requisition->issue_by = $request->issue_by;
        $material_requisition->issue_date = $request->issue_date;
        $material_requisition->issue_remarks = $request->issue_remarks;;
        $material_requisition->issue_time = $request->issue_time;;
        $flag = true;

        foreach ($request->product as $key => $product) {
            $material_requisition_details = MaterialRequisitionDetails::find($product['mrf_detail_id']);
            $material_requisition_details->issue_qty = $product['issue_qty'];
            if ($product['issue_qty'] < $material_requisition_details->request_qty) {
                $flag = false;
            }
            $material_requisition_details->issue_remarks = $product['issue_remarks'];
            $material_requisition_details->save();

            $prv_material_requisition_product_details_table = MaterialRequisitionProductDetails::where('mrf_detail_id', $product['mrf_detail_id'])->get();
            if ($prv_material_requisition_product_details_table) {
                foreach ($prv_material_requisition_product_details_table as $prv_material_requisition_product_details) {
                    $location = Location::where('area_id', $prv_material_requisition_product_details->area)
                        ->where('rack_id', $prv_material_requisition_product_details->rack)
                        ->where('level_id', $prv_material_requisition_product_details->level)
                        ->where('lot_no', $prv_material_requisition_product_details->lot_no)
                        ->first();
                    if ($location) {
                        // Example operation: reducing the location's quantity by the requisitioned quantity
                        $location->used_qty += $prv_material_requisition_product_details->qty;
                        $location->save();
                    }
                }
                MaterialRequisitionProductDetails::where('mrf_detail_id', $product['mrf_detail_id'])->delete();
            }
        }

        if ($flag) {
            $material_requisition->status = 'Issued';
        }
        $material_requisition->save();

        $storedData = json_decode($request->input('details'), true);

        $newArray = collect($storedData)->flatMap(function ($subArray) {
            return $subArray;
        })->sortBy('hiddenId')->values()->toArray();



        foreach ($newArray as $key => $material_requisition_product_details) {
            $material_requisition_product_details_table = new MaterialRequisitionProductDetails();
            $material_requisition_product_details_table->area = $material_requisition_product_details['area'];
            $material_requisition_product_details_table->rack = $material_requisition_product_details['rack'];
            $material_requisition_product_details_table->level = $material_requisition_product_details['level'];
            $material_requisition_product_details_table->lot_no = $material_requisition_product_details['lot_no'];
            $material_requisition_product_details_table->qty = $material_requisition_product_details['qty'];
            $material_requisition_product_details_table->mrf_detail_id = $material_requisition_product_details['mrf_detail_id'];
            $material_requisition_product_details_table->product_id = $material_requisition_product_details['hiddenId'];
            $material_requisition_product_details_table->save();

            $location = Location::where('area_id', $material_requisition_product_details['area'])
                ->where('rack_id', $material_requisition_product_details['rack'])
                ->where('level_id', $material_requisition_product_details['level'])
                ->where('lot_no', $material_requisition_product_details['lot_no'])
                ->first();
            if ($location) {
                // Example operation: reducing the location's quantity by the requisitioned quantity
                $location->used_qty -= $material_requisition_product_details['qty'];
                $location->save();
            } else {
                // Handle the case where the location was not found (optional)
                // For example, log an error or throw an exception
                Log::error("Location not found for area: {$material_requisition_product_details['area']}, rack: {$material_requisition_product_details['rack']}, level: {$material_requisition_product_details['level']}, lot_no: {$material_requisition_product_details['lot_no']}");
            }
        }

        NotificationController::Notification(
            'Material Requisition',
            'Issue',
            route('material_requisition.view', $material_requisition->id)
        );

        return redirect()->route('material_requisition.index')->with('custom_success', 'Material Requisition Issued');
    }

    public function receive($id)
    {
        if (!Auth::user()->hasPermissionTo('Material Requisition Receive')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $production_orders = DailyProductionPlanningDetail::all();
        $depts = Department::all();
        $machines = Machine::all();
        $products = Product::with('type_of_products', 'units')->get();
        $material_requisition = MaterialRequisition::find($id);
        $user = User::find($material_requisition->issue_by);
        $material_requisition_details = MaterialRequisitionDetails::where('material_requisition_id', $id)->get();
        $productIds = $material_requisition_details->pluck('product_id');
        $mrf_detail_ids = $material_requisition_details->pluck('id');
        $locations = AreaLocation::with('area', 'rack', 'level')->get();
        $material_requisition_product_details = MaterialRequisitionProductDetailsReceive::whereIn('mrf_detail_id', $mrf_detail_ids)->get();


        return view('wms.operations.material-requisition.receive', compact('user', 'production_orders', 'depts', 'machines', 'products', 'material_requisition', 'material_requisition_details', 'locations', 'material_requisition_product_details'));
    }

    public function received(Request $request, $id)
    {

        $material_requisition = MaterialRequisition::find($id);
        $material_requisition->rcv_by = $request->rcv_by;
        $material_requisition->rcv_date = $request->rcv_date;
        $material_requisition->rcv_remarks = $request->rcv_remarks;;
        $material_requisition->rcv_time = $request->rcv_time;;
        $material_requisition->status = 'Received';
        $material_requisition->save();
        NotificationController::Notification(
            'Material Requisition',
            'Receive',
            route('material_requisition.view', $material_requisition->id)
        );
        return redirect()->route('material_requisition.index')->with('success', 'Material Requisition Recevied');
    }

    public function rec_ack($id, Request $request)
    {
        $rcv_qty = $request->qty;
        $material_requisition_details = MaterialRequisitionDetails::find($id);
        $material_requisition_details->rcv_qty = $rcv_qty;
        $material_requisition_details->save();
        $material_requisition_product_details_receives = new MaterialRequisitionProductDetailsReceive();
        $material_requisition_product_details_receives->qty = $rcv_qty;
        $material_requisition_product_details_receives->mrf_detail_id = $id;
        $material_requisition_product_details_receives->product_id = $material_requisition_details->product_id;
        $material_requisition_product_details_receives->area = 1;
        $material_requisition_product_details_receives->save();
        if ($rcv_qty < $material_requisition_details->issue_qty) {
            $material_requisition = MaterialRequisition::find($material_requisition_details->material_requisition_id);
            $discrepancy['product_id'] = $material_requisition_details->product_id;
            $discrepancy['mrf_tr_id'] = $material_requisition->id;
            $discrepancy['order_no'] = $material_requisition->pro_order_no;
            $discrepancy['issue_qty'] = $material_requisition_details->issue_qty;
            $discrepancy['rcv_qty'] = $rcv_qty;
            $discrepancy['date'] = date('Y-m-d');
            DiscrepancyController::create($discrepancy);
            return redirect()->route('material_requisition.receive', $material_requisition_details->material_requisition_id)->with('success', 'Material Requisition Recevied, NOTE: Discrepancy Created !');
        }
        return redirect()->route('material_requisition.receive', $material_requisition_details->material_requisition_id)->with('success', 'Material Requisition Recevied');
    }
    public function rec_reject($id)
    {
        $material_requisition_details = MaterialRequisitionDetails::find($id);
        $material_requisition_details->rcv_qty = 0;
        $material_requisition_details->save();
        $material_requisition_product_details_receives = new MaterialRequisitionProductDetailsReceive();
        $material_requisition_product_details_receives->qty = $material_requisition_details->rcv_qty;
        $material_requisition_product_details_receives->mrf_detail_id = $id;
        $material_requisition_product_details_receives->product_id = $material_requisition_details->product_id;
        $material_requisition_product_details_receives->area = 2;
        $material_requisition_product_details_receives->save();
        return redirect()->route('material_requisition.receive', $material_requisition_details->material_requisition_id)->with('success', 'Material Requisition Recevied');
    }



    public function issue_print($id)
    {
        if (!Auth::user()->hasPermissionTo('Material Requisition Issue')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $material_requisition_details = MaterialRequisitionDetails::find($id);
        $mrf_id = $material_requisition_details->material_requisition_id;
        $material_requisition = MaterialRequisition::find($mrf_id);
        $machine_id = $material_requisition->machine;
        $machine = Machine::find($machine_id);
        $product_id = $material_requisition_details->product_id;
        $product = Product::find($product_id);
        $pdf = FacadePdf::loadView('wms.operations.material-requisition.preview', compact('material_requisition_details', 'material_requisition', 'machine', 'product'))->setPaper('a5');
        return $pdf->stream('material_requisition.issue.print');
    }
}
