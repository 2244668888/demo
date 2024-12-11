<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\InitailNo;
use App\Models\PurchaseOrderVerificationHistory;
use App\Models\PurchaseRequisition;
use App\Models\PurchasePlanning;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDetail;
use App\Models\Supplier;
use App\Models\PurchasePrice;
use App\Models\PoImportantNote;
use App\Models\Account;
use App\Models\AccountCategory;
use App\Models\Transaction;
use App\Models\Product;
use Carbon\Carbon;
use App\Models\SstPercentage;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Illuminate\Support\Facades\Auth;

class PurchaseOrderController extends Controller
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

            $query = PurchaseOrder::select('id', 'ref_no', 'quotation_ref_no','supplier_id','net_total','date','status','payment_status')->with('supplier');

            // Apply search if a search term is provided
            if (!empty($search)) {
                $searchLower = strtolower($search);
                $query->where(function ($q) use ($searchLower) {
                    $q
                    ->where('ref_no', 'like', '%' . $searchLower . '%')
                    ->orWhere('quotation_ref_no', 'like', '%' . $searchLower . '%')

                    ->orWhereHas('supplier', function ($query) use ($searchLower) {
                        $query->where('name', 'like', '%' . $searchLower . '%');
                    })
                    ->orWhere('net_total', 'like', '%' . $searchLower . '%')
                    ->orWhere('date', 'like', '%' . $searchLower . '%')
                    ->orWhere('status', 'like', '%' . $searchLower . '%')
                    ->orWhere('payment_status', 'like', '%' . $searchLower . '%');


                });
            }
            $results = null;

            if (!empty($columnsData)) {

                $sortableColumns = [
                    1 => 'ref_no',
                    2 => 'quotation_ref_no',
                    3 => 'supplier_id',
                    4 => 'net_total',
                    5 => 'date',
                    6 => 'status',
                    7 => 'payment_status',




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
                                $q->where('quotation_ref_no', 'like', '%' . $searchLower . '%');

                                // dd($q->get());
                                break;
                                case 3:
                                    $q->WhereHas('supplier', function ($query) use ($searchLower) {
                                        $query->where('name', 'like', '%' . $searchLower . '%');
                                    });


                                    break;
                                    case 4:
                                        $q->where('net_total', 'like', '%' . $searchLower . '%');
                                        break;
                                    case 5:
                                        $q->where('date', 'like', '%' . $searchLower . '%');

                                        break;
                                        case 6:
                                            $q->where('status', 'like', '%' . $searchLower . '%');
                                            break;
                                            case 7:
                                                $q->where('payment_status', 'like', '%' . $searchLower . '%');
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

                 $status = '';

                $row->date = Carbon::parse($row->date)->format('d/m/Y');

                $classes = '';
                $paymentClasses = '';

                if ($row->status == 'checked') {
                    $classes = 'badge border border-warning text-warning';
                } elseif ($row->status == 'in-progress') {
                    $classes = 'badge border border-light text-light';
                } elseif ($row->status == 'completed' || $row->status == 'verified') {
                    $classes = 'badge border border-success text-success';
                } elseif ($row->status == 'declined' || $row->status == 'cancelled') {
                    $classes = 'badge border border-danger text-danger';
                } else {
                    $classes = 'badge border border-secondary text-secondary';
                }

                if ($row->payment_status == 'due') {
                    $paymentClasses = 'badge border border-danger text-danger';
                } else {
                    $paymentClasses = 'badge border border-secondary text-success';
                }


                // $row->status = $status;
                $totalAmount = $row->net_total;
                $remainingBalance = $totalAmount - $row->payments->sum('paying_amount');

                $buttons = '<div class="d-flex"><a class="btn btn-success btn-sm mx-2"
                                        href="'. route('purchase_order.view', $row->id) .'" title="View"><i
                                            class="bi bi-eye"></i></a><a class="btn btn-danger btn-sm mx-2" href="' . route('purchase_order.preview', $row->id) . '" target="_blank" title="Preview"><i class="bi bi-file-pdf"></i></a>';

                if ($row->status == 'declined' || $row->status == 'in-progress') {
                    $buttons .= '<a class="btn btn-info btn-sm mx-2" href="' . route('purchase_order.edit', $row->id) . '" title="Edit"><i class="bi bi-pencil"></i></a>';
                    $buttons .= '<a class="btn btn-warning btn-sm mx-2" href="' . route('purchase_order.verification', ['id' => $row->id, 'action' => 'check']) . '" title="Check"><i class="bi bi-check"></i></a></div>';
                    $buttons .= '<button class="btn btn-danger btn-sm mx-2" data-bs-toggle="modal" data-bs-target="#' . $row->id . '">
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
                                                <form method="POST" action="' . route('purchase_order.destroy', $row->id) . '">
                                                    ' . csrf_field() . '
                                                    ' . method_field('DELETE') . '
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div></div>';
                } elseif ($row->status == 'completed') {
                   
                   
                } elseif ($row->status == 'checked') {
                    $buttons .= '<a class="btn btn-warning btn-sm mx-2" href="' . route('purchase_order.verification', ['id' => $row->id, 'action' => 'verify']) . '" title="Verify"><i class="bi bi-check-circle"></i></a></div>';
                } elseif ($row->status == 'verified') {
                    $buttons .= '<button class="btn btn-danger btn-sm mx-2" data-bs-toggle="modal" data-bs-target="#' . $row->id . '">
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
                                                <form method="POST" action="' . route('purchase_order.destroy', $row->id) . '">
                                                    ' . csrf_field() . '
                                                    ' . method_field('DELETE') . '
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div></div>';
                } elseif ($row->status == 'cancelled') {
                    $buttons .= '<button class="btn btn-danger btn-sm mx-2" data-bs-toggle="modal" data-bs-target="#' . $row->id . '">
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
                                                <form method="POST" action="' . route('purchase_order.destroy', $row->id) . '">
                                                    ' . csrf_field() . '
                                                    ' . method_field('DELETE') . '
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div></div>';
                }

                if ($row->payment_status == 'due' || $row->payment_status == 'partially_paid') {
                    $buttons .= '<div class="d-flex mt-2"><a class="btn btn-primary btn-sm mx-2" href="#" data-bs-toggle="modal" data-bs-target="#addPaymentModal" data-id="' . $row->id . '" data-total-amount="'. $row->net_total .'" data-remaining-balance="' . $remainingBalance . '" title="Add Payment">
                                    <i class="bi bi-cash-stack"></i>
                                 </a>';
                }

                $buttons .= '<a class="btn btn-secondary btn-sm mx-2" href="#" data-bs-toggle="modal" data-bs-target="#viewPaymentsModal" data-id="' . $row->id . '" title="View Payments">
                                <i class="bi bi-currency-exchange"></i>
                             </a></div>';

                $row->status = '<span class="' . $classes . '">' . $row->status . '</span>';
                $row->payment_status = '<span class="' . $paymentClasses . '">' . ucfirst($row->payment_status) . '</span>';

                $row->action = $buttons;


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

            $query = PurchaseOrder::select('id', 'ref_no', 'quotation_ref_no','supplier_id','net_total','date','status','payment_status')->with('supplier','payments');
            // Apply search if a search term is provided
            if (!empty($search)) {
                $searchLower = strtolower($search);
                $query->where(function ($q) use ($searchLower) {
                    $q
                    ->where('ref_no', 'like', '%' . $searchLower . '%')
                    ->orWhere('quotation_ref_no', 'like', '%' . $searchLower . '%')

                    ->orWhereHas('supplier', function ($query) use ($searchLower) {
                        $query->where('name', 'like', '%' . $searchLower . '%');
                    })
                    ->orWhere('net_total', 'like', '%' . $searchLower . '%')
                    ->orWhere('date', 'like', '%' . $searchLower . '%')
                    ->orWhere('status', 'like', '%' . $searchLower . '%')
                    ->orWhere('payment_status', 'like', '%' . $searchLower . '%');

                });
            }


            $sortableColumns = [
                1 => 'ref_no',
                2 => 'quotation_ref_no',
                3 => 'supplier_id',
                4 => 'net_total',
                5 => 'date',
                6 => 'status',
                7 => 'payment_status'




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

                $row->date = Carbon::parse($row->date)->format('d/m/Y');

                if ($row->status == 'checked') {
                    $classes = 'badge border border-warning text-warning';
                } elseif ($row->status == 'in-progress') {
                    $classes = 'badge border border-light text-light';
                } elseif ($row->status == 'completed' || $row->status == 'verified') {
                    $classes = 'badge border border-success text-success';
                } elseif ($row->status == 'declined' || $row->status == 'cancelled') {
                    $classes = 'badge border border-danger text-danger';
                } else {
                    $classes = 'badge border border-secondary text-secondary';
                }


                if ($row->payment_status == 'due') {
                    $paymentClasses = 'badge border border-danger text-danger';
                } else {
                    $paymentClasses = 'badge border border-secondary text-success';
                }
                // $row->status = $status;
                $totalAmount = $row->net_total;
                $remainingBalance = $totalAmount - $row->payments->sum('paying_amount');
                $buttons = '<div class="d-flex"><a class="btn btn-success btn-sm mx-2"
                                        href="'. route('purchase_order.view', $row->id) .'" title="View"><i
                                            class="bi bi-eye"></i></a><a class="btn btn-danger btn-sm mx-2" href="' . route('purchase_order.preview', $row->id) . '" target="_blank" title="Preview"><i class="bi bi-file-pdf"></i></a>';

                if ($row->status == 'declined' || $row->status == 'in-progress') {
                    $buttons .= '<a class="btn btn-info btn-sm mx-2" href="' . route('purchase_order.edit', $row->id) . '" title="Edit"><i class="bi bi-pencil"></i></a>';
                    $buttons .= '<a class="btn btn-warning btn-sm mx-2" href="' . route('purchase_order.verification', ['id' => $row->id, 'action' => 'check']) . '" title="Check"><i class="bi bi-check"></i></a>';
                    $buttons .= '<button class="btn btn-danger btn-sm mx-2" data-bs-toggle="modal" data-bs-target="#' . $row->id . '">
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
                                                <form method="POST" action="' . route('purchase_order.destroy', $row->id) . '">
                                                    ' . csrf_field() . '
                                                    ' . method_field('DELETE') . '
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div></div>';
                } elseif ($row->status == 'completed') {
                    $buttons .= '<a class="btn btn-info btn-sm mx-2" href="' . route('purchase_order.edit', $row->id) . '" title="Edit"><i class="bi bi-pencil"></i></a></div>';
                } elseif ($row->status == 'checked') {
                    $buttons .= '<a class="btn btn-warning btn-sm mx-2" href="' . route('purchase_order.verification', ['id' => $row->id, 'action' => 'verify']) . '" title="Verify"><i class="bi bi-check-circle"></i></a></div>';
                } elseif ($row->status == 'verified') {
                    $buttons .= '<a class="btn btn-info btn-sm mx-2" href="' . route('purchase_order.edit', $row->id) . '" title="Edit"><i class="bi bi-pencil"></i></a>';
                    $buttons .= '<button class="btn btn-danger btn-sm mx-2" data-bs-toggle="modal" data-bs-target="#' . $row->id . '">
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
                                                <form method="POST" action="' . route('purchase_order.destroy', $row->id) . '">
                                                    ' . csrf_field() . '
                                                    ' . method_field('DELETE') . '
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div></div>';
                } elseif ($row->status == 'cancelled') {
                    $buttons .= '<button class="btn btn-danger btn-sm mx-2" data-bs-toggle="modal" data-bs-target="#' . $row->id . '">
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
                                                <form method="POST" action="' . route('purchase_order.destroy', $row->id) . '">
                                                    ' . csrf_field() . '
                                                    ' . method_field('DELETE') . '
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div></div>';
                }
                if ($row->payment_status == 'due' || $row->payment_status == 'partially_paid') {
                    $buttons .= '<div class="d-flex mt-2"><a class="btn btn-primary btn-sm mx-2" href="#" data-bs-toggle="modal" data-bs-target="#addPaymentModal" data-id="' . $row->id . '" data-total-amount="'. $row->net_total .'" data-remaining-balance="' . $remainingBalance . '" title="Add Payment">
                                    <i class="bi bi-cash-stack"></i>
                                 </a>';
                }

                $buttons .= '<a class="btn btn-secondary btn-sm mx-2" href="#" data-bs-toggle="modal" data-bs-target="#viewPaymentsModal" data-id="' . $row->id . '" title="View Payments">
                                <i class="bi bi-currency-exchange"></i>
                             </a></div>';
                $row->status = '<span class="' . $classes . '">' . $row->status . '</span>';
                $row->payment_status = '<span class="' . $paymentClasses . '">' . ucfirst($row->payment_status) . '</span>';


                $row->action = $buttons;

            });

            return response()->json([
                'draw' => $draw,
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsTotal,
                'data' => $uom,
            ]);
        }
    }
    public function index()
    {
      if (
        Auth::user()->hasPermissionTo('Purchase Order List') ||
        Auth::user()->hasPermissionTo('Purchase Order Create') ||
        Auth::user()->hasPermissionTo('Purchase Order Edit') ||
        Auth::user()->hasPermissionTo('Purchase Order Verify') ||
        Auth::user()->hasPermissionTo('Purchase Order Preview') ||
        Auth::user()->hasPermissionTo('Purchase Order Check') ||
        Auth::user()->hasPermissionTo('Purchase Order View') ||
        Auth::user()->hasPermissionTo('Purchase Order Delete')
        ) {
            $purchase_orders = PurchaseOrder::all();
            $accounts = Account::with('category')->get();
            $bankCategory = AccountCategory::where('name', 'bank')->first();
            $bankCategoryId = $bankCategory ? $bankCategory->id : null;
            return view('erp.pvd.purchase-order.index', compact('purchase_orders','accounts', 'bankCategoryId'));
        }
        return back()->with('custom_errors', 'You don`t have the right permission');
    }

    public function create()
    {
      if (!Auth::user()->hasPermissionTo('Purchase Order Create')) {
        return back()->with('custom_errors', 'You don`t have the right permission');
      }
      $ref_no = '';
        $year = Carbon::now('Asia/Kuala_Lumpur')->format('y');
        $setting = InitailNo::where('screen', 'Purchase Order')->first();

        if ($setting) {
            $stock = PurchaseOrder::orderBy('id', 'DESC')->first();
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
            $stock = PurchaseOrder::orderBy('id', 'DESC')->first();
            if ($stock) {
                // Extract running_no from $stock->ref_no which is in format 'SR/running_no/year'
                $parts = explode('/', $stock->ref_no);
                if (count($parts) == 3) {
                    $running_no = (int) $parts[1] + 1;
                } else {
                    $running_no = 1; // Fallback in case the format is unexpected
                }
                $ref_no = 'PO/' . $running_no . '/' . $year;
            } else {
                $ref_no = 'PO/1/' . $year;
            }
        }
        $suppliers = Supplier::select('id', 'name')->get();
        $departments = Department::select('id', 'name')->get();
        $purchase_plannings = PurchasePlanning::with(['planning_detail','planning_detail.product','planning_detail.product.type_of_products','purchase_planning_suppliers.product.type_of_products','purchase_planning_suppliers.product.units','purchase_planning_suppliers.supplier','purchase_planning_suppliers.product.categories'])
        ->whereHas('verification', function($query) {
            $query->where('status', 'approved'); // Only include if there's an "approved" status
        })->get();
        $purchase_requisitions = PurchaseRequisition::with(['requisition_detail','requisition_detail.product','requisition_detail.product.type_of_products', 'requisition_detail.product.units', 'requisition_detail.product.categories'])->where('current_status', 'Approved')->get();
        $inportant_note = PoImportantNote::find(1);
        $purchase_price = PurchasePrice::all();
        $products_all = Product::with(['units','type_of_products', 'categories'])->get();
        $sale_tax = SstPercentage::find(1);
        // dd($purchase_requisitions);
        return view('erp.pvd.purchase-order.create', compact('departments', 'ref_no', 'suppliers', 'purchase_plannings', 'purchase_requisitions', 'inportant_note', 'purchase_price', 'sale_tax','products_all'));
    }
    public function store(Request $request)
    {
        if (!Auth::user()->hasPermissionTo('Purchase Order Create')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }

        $validated = $request->validate([
            'supplier_id' => [
                'required'
            ],
            'date' => [
                'required'
            ],
            'status' => [
                'required'
            ],
            'products' => [
                'required'
            ],
            'pr_id' => [
                'required_if:pp_pr,1'
            ],
            'pp_id' => [
                'required_unless:pp_pr,1'
            ]
        ]);

        $purchase_order = new PurchaseOrder();
        $purchase_order->supplier_id = $request->supplier_id;
        $purchase_order->ref_no = $request->ref_no;
        $purchase_order->date = $request->date;
        $purchase_order->quotation_ref_no = $request->quotation_ref_no;
        $purchase_order->pp_pr = $request->pp_pr;
        if($request->pp_pr == 1){
            if($request->pr_id == 'manual'){
            }else{
                $purchase_order->pr_id = $request->pr_id;
            }
        }else{
            if($request->pp_id == 'manual'){
            }else{
                $purchase_order->pp_id = $request->pp_id;
            }
        }
        if($request->file('attachment')){
            $file = $request->file('attachment');
            $filename = date('YmdHis').$file->getClientOriginalName();
            $file->move('order-attachments', $filename);
            $purchase_order->attachment =  $filename;
        }
        $purchase_order->payment_term = $request->payment_term;
        $purchase_order->department_id = $request->department_id;
        $purchase_order->important_note = $request->important_note;
        $purchase_order->status = $request->status;
        $purchase_order->required_date = $request->required_date;
        $purchase_order->bulk_required_date = $request->bulk_required_date;
        $purchase_order->bulk_wo_discount = $request->bulk_wo_discount;

        $purchase_order->total_qty = $request->total_qty ?? 0;
        $purchase_order->total_sale_tax = $request->total_sale_tax ?? 0;
        $purchase_order->total_discount = $request->total_discount ?? 0;
        $purchase_order->net_total = $request->net_total ?? 0;
        $purchase_order->payment_status = 'due';

        $purchase_order->created_by = Auth::user()->id;
        $purchase_order->save();

        foreach($request->products as $products){
            $purchase_order_detail = new PurchaseOrderDetail();
            $purchase_order_detail->purchase_order_id = $purchase_order->id;
            $purchase_order_detail->product_id = $products['product_id'] ?? null;
            $purchase_order_detail->required_date = $products['required_date'] ?? null;
            $purchase_order_detail->price = $products['price'] ?? 0;
            $purchase_order_detail->qty = $products['qty'] ?? 0;
            $purchase_order_detail->disc = $products['disc'] ?? 0;
            $purchase_order_detail->disc_percent = $products['disc_percent'] ?? 0;
            $purchase_order_detail->sale_tax = $products['sale_tax'] ?? 0;
            $purchase_order_detail->sale_tax_percent = $products['sale_tax_percent'] ?? 0;
            $purchase_order_detail->total = $products['total'] ?? 0;
            $purchase_order_detail->wo_discount = $products['wo_discount'] ?? 0;
            $purchase_order_detail->save();
        }

        $netTotal = $purchase_order->net_total;
        $inventoryAccount = Account::firstOrCreate(
            ['name' => 'inventory'],
            ['type' => 'asset', 'opening_balance' => 0, 'categoryType' => 'current']

        );
        Transaction::create([
            'account_id' => $inventoryAccount->id,
            'type' => 'debit',
            'amount' => $netTotal,
            'description' => 'Purchase Order No: ' . $purchase_order->id . ' - Inventory Addition',
            'created_at' => $purchase_order->date
        ]);

        $accountsPayableAccount = Account::firstOrCreate(
            ['name' => 'Account Payable'],
            ['type' => 'liability', 'opening_balance' => 0, 'categoryType' => 'current']
        );
        Transaction::create([
            'account_id' => $accountsPayableAccount->id,
            'type' => 'credit',
            'amount' => $netTotal,
            'description' => 'Purchase Order No: ' . $purchase_order->id . ' - Liability to Supplier',
            'created_at' => $purchase_order->date
        ]);

        NotificationController::Notification('Purchase Order', 'Create', '' . route('purchase_order.view', $purchase_order->id) . '');

        return redirect()->route('purchase_order.index')->with('custom_success', 'Purchase Order has been Created Successfully!');
    }

    public function edit($id)
    {
        if (!Auth::user()->hasPermissionTo('Purchase Order Edit')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $purchase_order = PurchaseOrder::find($id);
        $purchase_order_details = PurchaseOrderDetail::where('purchase_order_id', $id)->get();
        $suppliers = Supplier::select('id', 'name')->get();
        $departments = Department::select('id', 'name')->get();
        $inportant_note = PoImportantNote::find(1);
        $sale_tax = SstPercentage::find(1);
        $purchase_plannings = PurchasePlanning::with(['planning_detail','planning_detail.product','planning_detail.product.type_of_products','purchase_planning_suppliers.product.type_of_products','purchase_planning_suppliers.product.units','purchase_planning_suppliers.supplier','purchase_planning_suppliers.product.categories'])
        ->whereHas('verification', function($query) {
            $query->where('status', 'approved'); // Only include if there's an "approved" status
        })->get();
        $purchase_requisitions = PurchaseRequisition::with(['requisition_detail','requisition_detail.product','requisition_detail.product.type_of_products', 'requisition_detail.product.units', 'requisition_detail.product.categories'])->where('current_status', 'Approved')->get();
        $purchase_price = PurchasePrice::all();
        $products_all = Product::with(['units','type_of_products','categories'])->get();
        return view('erp.pvd.purchase-order.edit', compact('sale_tax','products_all','purchase_price','purchase_requisitions','purchase_plannings','departments', 'suppliers', 'purchase_order', 'purchase_order_details', 'inportant_note'));
    }

    public function update(Request $request, $id)
    {
        if (!Auth::user()->hasPermissionTo('Purchase Order Edit')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }

        $validated = $request->validate([
            'supplier_id' => [
                'required'
            ],
            'date' => [
                'required'
            ],
            'status' => [
                'required'
            ],
            'products' => [
                'required'
            ]
        ]);

        $purchase_order = PurchaseOrder::find($id);
        $purchase_order->supplier_id = $request->supplier_id;
        $purchase_order->date = $request->date;
        $purchase_order->quotation_ref_no = $request->quotation_ref_no;
        $purchase_order->payment_term = $request->payment_term;
        $purchase_order->department_id = $request->department_id;
        $purchase_order->important_note = $request->important_note;
        $purchase_order->status = $request->status;
        $purchase_order->required_date = $request->required_date;
        $purchase_order->bulk_required_date = $request->bulk_required_date;
        $purchase_order->bulk_wo_discount = $request->bulk_wo_discount;
        if($request->file('attachment')){
            $file = $request->file('attachment');
            $filename = date('YmdHis').$file->getClientOriginalName();
            $file->move('order-attachments', $filename);
            $purchase_order->attachment =  $filename;
        }
        $purchase_order->total_qty = $request->total_qty ?? 0;
        $purchase_order->total_sale_tax = $request->total_sale_tax ?? 0;
        $purchase_order->total_discount = $request->total_discount ?? 0;
        $purchase_order->net_total = $request->net_total ?? 0;

        $purchase_order->created_by = Auth::user()->id;
        $purchase_order->save();

        PurchaseOrderDetail::where('purchase_order_id', $id)->delete();
        foreach($request->products as $products){
            $purchase_order_detail = new PurchaseOrderDetail();
            $purchase_order_detail->purchase_order_id = $purchase_order->id;
            $purchase_order_detail->product_id = $products['product_id'] ?? null;
            $purchase_order_detail->required_date = $products['required_date'] ?? null;
            $purchase_order_detail->price = $products['price'] ?? 0;
            $purchase_order_detail->qty = $products['qty'] ?? 0;
            $purchase_order_detail->disc = $products['disc'] ?? 0;
            $purchase_order_detail->disc_percent = $products['disc_percent'] ?? 0;
            $purchase_order_detail->sale_tax = $products['sale_tax'] ?? 0;
            $purchase_order_detail->sale_tax_percent = $products['sale_tax_percent'] ?? 0;
            $purchase_order_detail->total = $products['total'] ?? 0;
            $purchase_order_detail->wo_discount = $products['wo_discount'] ?? 0;
            $purchase_order_detail->save();
        }

        return redirect()->route('purchase_order.index')->with('custom_success', 'Purchase Order has been Updated Successfully!');
    }

    public function view($id)
    {
        if (!Auth::user()->hasPermissionTo('Purchase Order View')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $purchase_order = PurchaseOrder::find($id);
        $purchase_order_details = PurchaseOrderDetail::where('purchase_order_id', $id)->get();
        $purchase_order_histories = PurchaseOrderVerificationHistory::where('purchase_order_id', $id)->with('user.department')->get();
        return view('erp.pvd.purchase-order.view', compact('purchase_order', 'purchase_order_details','purchase_order_histories'));
    }

    public function preview(Request $request, $id){
        if (!Auth::user()->hasPermissionTo('Purchase Order Preview')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $purchase_order = PurchaseOrder::find($id);
        $purchase_order_details = PurchaseOrderDetail::where('purchase_order_id', $id)->get();

        $pdf = FacadePdf::loadView('erp.pvd.purchase-order.preview', [
            'purchase_order' => $purchase_order,
            'purchase_order_details' => $purchase_order_details,
            'include_terms' => true 
        ])->setPaper('a4');

        return $pdf->stream('purchase-order.preview');
    }

    public function verification($id, $action)
    {
        $purchase_order = PurchaseOrder::find($id);
        $purchase_order_details = PurchaseOrderDetail::where('purchase_order_id', $id)->get();
        $purchase_order_histories = PurchaseOrderVerificationHistory::where('purchase_order_id', $id)->with('user.department')->get();
        return view('erp.pvd.purchase-order.verification', compact('purchase_order', 'purchase_order_details', 'action','purchase_order_histories'));
    }

    public function check($id)
    {
        if (!Auth::user()->hasPermissionTo('Purchase Order Check')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }

        $purchase_order = PurchaseOrder::find($id);
        $purchase_order->status = 'checked';
        $purchase_order->checked_by = Auth::user()->user_name;
        $purchase_order->checked_by_time = Carbon::now('Asia/Kuala_Lumpur')->format('d-m-Y H:i:s');
        $purchase_order->save();

        $purchase_order_history = new PurchaseOrderVerificationHistory();
        $purchase_order_history->purchase_order_id = $id;
        $purchase_order_history->status = 'checked';
        $purchase_order_history->action_by = Auth::user()->id;
        $purchase_order_history->date = Carbon::now('Asia/Kuala_Lumpur')->format('d-m-Y H:i:s');
        $purchase_order_history->save();

        NotificationController::Notification('Purchase Order', 'Check', '' . route('purchase_order.view', $purchase_order->id) . '');


        return redirect()->route('purchase_order.index')->with('custom_success', 'Purchase Order has been Successfully Checked!');
    }

    public function verify($id)
    {
        if (!Auth::user()->hasPermissionTo('Purchase Order Verify')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }

        $purchase_order = PurchaseOrder::find($id);
        $purchase_order->status = 'verified';
        $purchase_order->verify_by = Auth::user()->user_name;
        $purchase_order->verify_by_time = Carbon::now('Asia/Kuala_Lumpur')->format('d-m-Y H:i:s');
        $purchase_order->save();

        $purchase_order_history = new PurchaseOrderVerificationHistory();
        $purchase_order_history->purchase_order_id = $id;
        $purchase_order_history->status = 'verified';
        $purchase_order_history->action_by = Auth::user()->id;
        $purchase_order_history->date = Carbon::now('Asia/Kuala_Lumpur')->format('d-m-Y H:i:s');
        $purchase_order_history->save();

        NotificationController::Notification('Purchase Order', 'Verify', '' . route('purchase_order.view', $purchase_order->id) . '');

        return redirect()->route('purchase_order.index')->with('custom_success', 'Purchase Order has been Successfully Verified(HOD)!');
    }

    public function decline_cancel(Request $request, $id)
    {
        if($request->decline_cancel == 'decline'){
            if (!Auth::user()->hasPermissionTo('Purchase Order Decline')) {
                return back()->with('custom_errors', 'You don`t have the right permission');
            }
        }else{
            if (!Auth::user()->hasPermissionTo('Purchase Order Cancel')) {
                return back()->with('custom_errors', 'You don`t have the right permission');
            }
        }

        $purchase_order = PurchaseOrder::find($id);
        $purchase_order->status = ($request->decline_cancel == 'decline') ? 'declined' : 'cancelled';
        $purchase_order->verify_by = Auth::user()->user_name;
        $purchase_order->verify_by_time = Carbon::now('Asia/Kuala_Lumpur')->format('d-m-Y H:i:s');
        $purchase_order->reason = $request->reason;
        $purchase_order->save();

        $purchase_order_history = new PurchaseOrderVerificationHistory();
        $purchase_order_history->purchase_order_id = $id;
        $purchase_order_history->status = ($request->decline_cancel == 'decline') ? 'declined' : 'cancelled';
        $purchase_order_history->action_by = Auth::user()->id;
        $purchase_order_history->date = Carbon::now('Asia/Kuala_Lumpur')->format('d-m-Y H:i:s');
        $purchase_order_history->save();

        if($request->decline_cancel == 'decline'){
            return redirect()->route('purchase_order.index')->with('custom_success', 'Purchase Order has been Successfully Declined!');
        }
        return redirect()->route('purchase_order.index')->with('custom_success', 'Purchase Order has been Successfully Cancelled!');
    }

    public function destroy(Request $request, $id){
        if (!Auth::user()->hasPermissionTo('Purchase Order Delete')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $purchase_order = PurchaseOrder::find($id);
        PurchaseOrderDetail::where('purchase_order_id', $id)->delete();
        $purchase_order->delete();
        return redirect()->route('purchase_order.index')->with('custom_success', 'Purchase Order has been Deleted Successfully!');
    }
}
