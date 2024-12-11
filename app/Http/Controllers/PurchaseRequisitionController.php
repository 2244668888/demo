<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Designation;
use App\Models\InitailNo;
use App\Models\PrApproval;
use App\Models\Product;
use App\Models\PurchasePrice;
use App\Models\PurchaseRequisition;
use App\Models\PurchaseRequisitionDetail;
use App\Models\PurchaseRequisitionStatus;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class PurchaseRequisitionController extends Controller
{

    public function Data(Request $request)
    {
        if ($request->ajax()) {
            $query = PurchaseRequisition::select(
                'purchase_requisitions.id',
                'purchase_requisitions.pr_no',
                'purchase_requisitions.department_id',
                'purchase_requisitions.date',
                'purchase_requisitions.total',
                'purchase_requisitions.status',
                'purchase_requisitions.category',
                'purchase_requisitions.requested_by',
                'purchase_requisitions.current_status',
                'purchase_requisitions.verified_by',
                'purchase_requisitions.verified_by_id'
            )->with([
                'user',
                'department',
                'verified_by_relation.designation'
            ]);

            return DataTables::of($query)
                ->filter(function ($query) use ($request) {
                    if ($request->has('search') && $request->search['value']) {
                        // Apply global search
                        $searchTerm = $request->search['value'];
                        $query->where(function ($q) use ($searchTerm) {
                            $q->where('pr_no', 'like', '%' . $searchTerm . '%')
                                ->orWhere('total', 'like', '%' . $searchTerm . '%')
                                ->orWhereHas('department', function ($q) use ($searchTerm) {
                                    $q->where('name', 'like', '%' . $searchTerm . '%');
                                })
                                ->orWhereHas('user', function ($q) use ($searchTerm) {
                                    $q->where('user_name', 'like', '%' . $searchTerm . '%');
                                })
                                ->orWhere('date', 'like', '%' . $searchTerm . '%')
                                ->orWhere('status', 'like', '%' . $searchTerm . '%')
                                ->orWhere('category', 'like', '%' . $searchTerm . '%')
                                ->orWhere('current_status', 'like', '%' . $searchTerm . '%');
                        });
                    } else {
                        if ($request->pr_no) {
                            $query->where('pr_no', 'like', '%' . $request->pr_no . '%');
                        }
                        if ($request->department) {
                            $query->whereHas('department', function ($q) use ($request) {
                                $q->where('name', 'like', '%' . $request->department . '%');
                            });
                        }
                        if ($request->date) {
                            $query->where('date', 'like', '%' . $request->date . '%');
                        }
                        if ($request->total) {
                            $query->where('total', 'like', '%' . $request->total . '%');
                        }
                        if ($request->pr_status) {
                            $query->where('status', 'like', '%' . $request->pr_status . '%');
                        }
                        if ($request->category) {
                            $query->where('category', 'like', '%' . $request->category . '%');
                        }
                        if ($request->request_by) {
                            $query->whereHas('user', function ($q) use ($request) {
                                $q->where('user_name', 'like', '%' . $request->request_by . '%');
                            });
                        }
                        if ($request->name) {
                            $query->whereHas('verified_by_relation', function ($q) use ($request) {
                                $q->where('user_name', 'like', '%' . $request->name . '%');
                            });
                        }
                        if ($request->designation) {
                            $query->whereHas('verified_by_relation.designation', function ($q) use ($request) {
                                $q->where('name', 'like', '%' . $request->designation . '%');
                            });
                        }
                        if ($request->current_status) {
                            $query->where('current_status', 'like', '%' . $request->current_status . '%');
                        }
                    }
                })
                ->editColumn('date', function ($row) {
                    return Carbon::parse($row->date)->format('d/m/Y');
                })
                ->editColumn('current_status', function ($row) {
                    $statusSpan = '';
                    $classes = '';
                    $status = strtolower($row->current_status);

                    switch ($status) {
                        case 'requested':
                            $classes = 'badge border border-light text-light';
                            break;
                        case 'checked':
                            $classes = 'badge border border-warning text-warning';
                            break;
                        case 'verified':
                            $classes = 'badge border border-info text-info';
                            break;
                        case 'declined':
                            $classes = 'badge border  border-danger text-danger';
                            break;
                        case 'cancelled':
                            $classes = 'badge border border-danger text-danger';
                            break;
                        case 'approved':
                            $classes = 'badge border border-success text-success';
                            break;
                    }

                    if ($row->verified_by == '') {
                        if ($row->current_status == 'Requested') {
                            $statusSpan = 'Requested';
                        } elseif ($row->current_status == 'Declined') {
                            $statusSpan = 'Declined';
                        }
                    } elseif ($row->verified_by == 'hod') {
                        if ($row->current_status == 'Declined') {
                            $statusSpan = 'HOD Declined';
                        } elseif ($row->current_status == 'Cancelled') {
                            $statusSpan = 'HOD Cancelled';
                        } elseif ($row->current_status == 'Verified') {
                            $statusSpan = 'HOD Verified';
                        }
                    } elseif ($row->verified_by == 'acc') {
                        if ($row->current_status == 'Declined') {
                            $statusSpan = 'Accounts Declined';
                        } elseif ($row->current_status == 'Cancelled') {
                            $statusSpan = 'Accounts Cancelled';
                        } elseif ($row->current_status == 'Verified') {
                            $statusSpan = 'Accounts Verified';
                        }
                    } elseif ($row->verified_by == 'head') {
                        if ($row->current_status == 'Declined') {
                            $statusSpan = 'Head Declined';
                        } elseif ($row->current_status == 'Cancelled') {
                            $statusSpan = 'Head Cancelled';
                        } elseif ($row->current_status == 'Verified') {
                            $statusSpan = 'Head Verified';
                        }
                    }

                    if ($row->current_status == 'Approved') {
                        $statusSpan = 'Approved';
                    }

                    return '<span class="' . $classes . '">' . $statusSpan . '</span>';
                })
                ->addColumn('action', function ($row) {
                    $actions = '<div class="d-flex">';
                    if ($row->current_status == 'Requested' || $row->current_status == 'Declined') {
                        $actions .= '<a title="Edit" class="btn btn-info btn-sm mx-2" href="' . route('purchase_requisition.edit', $row->id) . '"><i class="bi bi-pencil"></i></a>';
                        $actions .= '<a title="Approve" class="btn btn-warning btn-sm mx-2" href="' . route('purchase_requisition.changestatus', [$row->id, 'Verified']) . '"><i class="bi bi-check"></i></a>';
                        $actions .= '<button class="btn btn-danger btn-sm mx-2" data-bs-toggle="modal" data-bs-target="#' . $row->id . '">
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
                                                        <form method="POST" action="' . route('purchase_requisition.destroy', $row->id) . '">
                                                            ' . csrf_field() . '
                                                            ' . method_field('DELETE') . '
                                                            <button type="submit" class="btn btn-danger">Delete</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>';
                    } else if ($row->current_status == 'Verified') {
                        $actions .= '<a title="Approve" class="btn btn-warning btn-sm mx-2" href="' . route('purchase_requisition.changestatus', [$row->id, 'Verified']) . '"><i class="bi bi-check"></i></a>';
                    }

                    $actions .= '<a title="View" class="btn btn-success btn-sm mx-2" href="' . route('purchase_requisition.view', $row->id) . '"><i class="bi bi-eye"></i></a></div>';
                    return $actions;
                })
                ->rawColumns(['current_status', 'action'])
                ->make(true);
        }
    }
    public function index()
    {
        if (
            Auth::user()->hasPermissionTo('Purchase Requisition List') ||
            Auth::user()->hasPermissionTo('Purchase Requisition Create') ||
            Auth::user()->hasPermissionTo('Purchase Requisition Edit') ||
            Auth::user()->hasPermissionTo('Purchase Requisition View') ||
            Auth::user()->hasPermissionTo('Purchase Requisition Delete')
        ) {
            return view('erp.pvd.purchase-requisition.index');
        }
        return back()->with('custom_errors', 'You don`t have the right permission');
    }
    public function create()
    {
        if (!Auth::user()->hasPermissionTo('Purchase Requisition Create')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $department = Department::find(Auth::user()->department_id);
        $products = Product::with('type_of_products', 'units', 'categories')->get();
        $purchase_prices = PurchasePrice::whereIn('product_id', $products->pluck('id'))
            ->where('status', '=', 'verified')
            ->orderBy('date', 'desc')
            ->get();
        $pr_no = '';
        $year = Carbon::now('Asia/Kuala_Lumpur')->format('y');
        $setting = InitailNo::where('screen', 'Purchase Requisition')->first();
        if ($setting) {
            $pr = PurchaseRequisition::orderBy('id', 'DESC')->first();
            if ($pr) {
                // Extract running_no from $pr->pr_no which is in format 'PR/running_no/year'
                $parts = explode('/', $pr->pr_no);
                if (count($parts) == 3) {
                    $running_no = (int) $parts[1] + 1;
                } else {
                    $running_no = 1; // Fallback in case the format is unexpected
                }
                $pr_no = $setting->ref_no . '/' . $running_no . '/' . $year;
            } else {
                $pr_no = $setting->ref_no . '/' . $setting->running_no . '/' . $year;
            }
        } else {
            $pr = PurchaseRequisition::orderBy('id', 'DESC')->first();
            if ($pr) {
                // Extract running_no from $pr->ref_no which is in format 'PR/running_no/year'
                $parts = explode('/', $pr->pr_no);
                if (count($parts) == 3) {
                    $running_no = (int) $parts[1] + 1;
                } else {
                    $running_no = 1; // Fallback in case the format is unexpected
                }
                $pr_no = 'PR/' . $running_no . '/' . $year;
            } else {
                $pr_no = 'PR/1/' . $year;
            }
        }
        return view('erp.pvd.purchase-requisition.create', compact('products', 'department', 'pr_no', 'purchase_prices'));
    }
    public function store(Request $request)
    {
        if (!Auth::user()->hasPermissionTo('Purchase Requisition Create')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $request->validate([
            'pr_no' => ['required'],
            'status' => ['required'],
            'date' => ['required'],
            'category' => ['required'],
            'total' => ['required'],
        ]);



        $purchase_requisition = new PurchaseRequisition();
        $purchase_requisition->pr_no = $request->pr_no;
        $purchase_requisition->department_id = $request->department_id;
        $purchase_requisition->status = $request->status;
        $purchase_requisition->date = $request->date;
        $purchase_requisition->require_date = $request->require_date;
        $purchase_requisition->category = $request->category;
        $purchase_requisition->category_other = $request->category_other;
        $purchase_requisition->total = $request->total;
        $purchase_requisition->remarks = $request->remarks;
        $purchase_requisition->requested_by = Auth::user()->id;
        $purchase_requisition->verified_by_id = Auth::user()->id;
        if ($request->file('attachment')) {
            $file = $request->file('attachment');
            $filename = date('YmdHis') . $file->getClientOriginalName();
            $file->move('pr-attachments', $filename);
            $purchase_requisition->attachment =  $filename;
        }
        $purchase_requisition->save();
        $purchase_requisition_id = $purchase_requisition->id;

        foreach ($request->products as $product) {
            $purchase_requisition_detals = new PurchaseRequisitionDetail();
            $purchase_requisition_detals->product_id = $product['product_id'];
            $purchase_requisition_detals->purchase_requisition_id = $purchase_requisition_id;
            $purchase_requisition_detals->price = $product['price'];
            $purchase_requisition_detals->qty = $product['qty'];
            $purchase_requisition_detals->total = $product['total'];
            $purchase_requisition_detals->purpose = $product['purpose'];
            $purchase_requisition_detals->save();
        }    

        NotificationController::Notification('Purchase Requisition', 'Create', '' . route('purchase_requisition.view', $purchase_requisition->id) . '');

        return redirect()->route('purchase_requisition.index')->with('custom_success', 'Purchase Requisition Created Successfully.');
    }
    public function view($id)
    {
        if (!Auth::user()->hasPermissionTo('Purchase Requisition View')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $purchase_requisitions = PurchaseRequisition::with('department', 'user')->find($id);
        $purchase_requisition_details = PurchaseRequisitionDetail::where('purchase_requisition_id', $id)->with('product')->get();
        $purchase_requisition_statuses = PurchaseRequisitionStatus::where('purchase_requisition_id', $id)->get();
        return view('erp.pvd.purchase-requisition.view', compact('purchase_requisitions', 'purchase_requisition_details', 'purchase_requisition_statuses'));
    }
    public function edit($id)
    {
        if (!Auth::user()->hasPermissionTo('Purchase Requisition Edit')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $products = Product::with('type_of_products', 'units')->get();
        $purchase_prices = PurchasePrice::whereIn('product_id', $products->pluck('id'))
            ->where('status', '=', 'verified')
            ->orderBy('date', 'desc')
            ->get();
        $purchase_requisitions = PurchaseRequisition::with('department', 'user')->find($id);
        $department = Department::find(Auth::user()->department_id);
        $purchase_requisition_details = PurchaseRequisitionDetail::where('purchase_requisition_id', $id)->with('product')->get();
        return view('erp.pvd.purchase-requisition.edit', compact('purchase_requisition_details', 'purchase_requisitions', 'products', 'department', 'purchase_prices'));
    }
    public function update(Request $request, $id)
    {
        if (!Auth::user()->hasPermissionTo('Purchase Requisition Edit')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $request->validate([
            'pr_no' => ['required'],
            'status' => ['required'],
            'date' => ['required'],
            'category' => ['required'],
            'total' => ['required'],
        ]);



        $purchase_requisition = PurchaseRequisition::find($id);
        $purchase_requisition->pr_no = $request->pr_no;
        $purchase_requisition->department_id = $request->department_id;
        $purchase_requisition->status = $request->status;
        $purchase_requisition->current_status = 'prepared';
        $purchase_requisition->date = $request->date;
        $purchase_requisition->require_date = $request->require_date;
        $purchase_requisition->category = $request->category;
        $purchase_requisition->category_other = $request->category_other;
        $purchase_requisition->total = $request->total;
        $purchase_requisition->remarks = $request->remarks;
        $purchase_requisition->requested_by = Auth::user()->id;
        if ($request->file('attachment')) {
            $file = $request->file('attachment');
            $filename = date('YmdHis') . $file->getClientOriginalName();
            $file->move('pr-attachments', $filename);
            $purchase_requisition->attachment =  $filename;
        }
        $purchase_requisition->save();
        PurchaseRequisitionDetail::where('purchase_requisition_id', $id)->delete();

        foreach ($request->products as $product) {
            $purchase_requisition_detals = new PurchaseRequisitionDetail();
            $purchase_requisition_detals->product_id = $product['product_id'];
            $purchase_requisition_detals->purchase_requisition_id = $id;
            $purchase_requisition_detals->price = $product['price'];
            $purchase_requisition_detals->qty = $product['qty'];
            $purchase_requisition_detals->total = $product['total'];
            $purchase_requisition_detals->purpose = $product['purpose'];
            $purchase_requisition_detals->save();
        }
        $purchase_requisition_status = new PurchaseRequisitionStatus();
        $purchase_requisition_status->purchase_requisition_id = $id;
        $purchase_requisition_status->status = 'Requested';
        $purchase_requisition_status->approved_by = Auth::user()->id;
        $purchase_requisition_status->date = Carbon::now();
        $purchase_requisition_status->save();

        return redirect()->route('purchase_requisition.index')->with('custom_success', 'Purchase Requisition Created Successfully.');
    }
    public function destroy($id)
    {
        if (!Auth::user()->hasPermissionTo('Purchase Requisition Delete')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        PurchaseRequisitionDetail::where('purchase_requisition_id', $id)->delete();
        PurchaseRequisitionStatus::where('purchase_requisition_id', $id)->delete();
        PurchaseRequisition::find($id)->delete();
        return redirect()->route('purchase_requisition.index')->with('custom_success', 'Purchase Requisition Deleted Successfully.');
    }

    public function changestatus($id, $status)
    {
        $purchase_requisitions = PurchaseRequisition::with('department', 'user')->find($id);
        $department = Department::find($purchase_requisitions->department_id);
        $purchase_requisition_details = PurchaseRequisitionDetail::where('purchase_requisition_id', $id)->with('product')->get();
        if ($status == 'Verified' && $purchase_requisitions->verified_by == 'hod') {
            if (Auth::user()->hasPermissionTo('Purchase Requisition Verify ACC')) {
                return view('erp.pvd.purchase-requisition.verification', compact('purchase_requisitions', 'purchase_requisition_details', 'department', 'status'));
            } else {
                return back()->with('custom_errors', 'You don`t have the right permission Accounts Permission required');
            }
        }
        if ($status == 'Verified' && $purchase_requisitions->verified_by == '') {
            if (Auth::user()->hasPermissionTo('Purchase Requisition Verify HOD')) {
                return view('erp.pvd.purchase-requisition.verification', compact('purchase_requisitions', 'purchase_requisition_details', 'department', 'status'));
            } else {
                return back()->with('custom_errors', 'You don`t have the right permission HOD Permission required');
            }
        }
        if ($status == 'Verified' && $purchase_requisitions->verified_by == 'acc') {
            if (Auth::user()->hasPermissionTo('Purchase Requisition Verify HOD')) {
                return view('erp.pvd.purchase-requisition.verification', compact('purchase_requisitions', 'purchase_requisition_details', 'department', 'status'));
            } else {
                return back()->with('custom_errors', 'You don`t have the right permission HOD Permission required');
            }
        }
        if ($status == 'Verified' && $purchase_requisitions->verified_by == 'head') {
            if (Auth::user()->hasPermissionTo('Purchase Requisition Approve')) {
                return view('erp.pvd.purchase-requisition.verification', compact('purchase_requisitions', 'purchase_requisition_details', 'department', 'status'));
            } else {
                return back()->with('custom_errors', 'You don`t have the right permission HOD Permission required');
            }
        }
        if ($status == 'Declined') {
            if (!Auth::user()->hasPermissionTo('Purchase Requisition Decline')) {
                return back()->with('custom_errors', 'You don`t have the right permission');
            }
            return view('erp.pvd.purchase-requisition.verification', compact('purchase_requisitions', 'purchase_requisition_details', 'department', 'status'));
        }
        if ($status == 'Cancelled') {
            if (!Auth::user()->hasPermissionTo('Purchase Requisition Cancel')) {
                return back()->with('custom_errors', 'You don`t have the right permission');
            }
            return view('erp.pvd.purchase-requisition.verification', compact('purchase_requisitions', 'purchase_requisition_details', 'department', 'status'));
        }
    }

    public function verify(Request $request, $id, $verified_by)
    {
        if ($verified_by == 'hod') {
            if (!Auth::user()->hasPermissionTo('Purchase Requisition Verify HOD')) {
                return back()->with('custom_errors', 'You don`t have the right permission');
            }
            $verified_by_id = Auth::user()->id;
            $purchase_requisition = PurchaseRequisition::find($id);
            $purchase_requisition->verified_by = $verified_by;
            $purchase_requisition->verified_by_id = $verified_by_id;
            $purchase_requisition->current_status = 'Verified';
            $purchase_requisition->save();
            $purchase_requisition_status = new PurchaseRequisitionStatus();
            $purchase_requisition_status->purchase_requisition_id = $id;
            $purchase_requisition_status->status = 'Verified';
            $purchase_requisition_status->approved_by = $request->approved_by;
            $user_dept = User::find($request->approved_by);
            $purchase_requisition_status->department_id = $user_dept->department_id;
            $purchase_requisition_status->date = Carbon::now();
            $purchase_requisition_status->save();
            NotificationController::Notification('Purchase Requisition', 'Verify HOD', '' . route('purchase_requisition.view', $purchase_requisition->id) . '');
        }
        // if ($verified_by == 'acc') {
        //     if (!Auth::user()->hasPermissionTo('Purchase Requisition Verify HOD')) {
        //         return back()->with('custom_errors', 'You don`t have the right permission');
        //     }
        //     $verified_by_id = Auth::user()->id;
        //     $purchase_requisition = PurchaseRequisition::find($id);
        //     $purchase_requisition->verified_by = $verified_by;
        //     $purchase_requisition->verified_by_id = $verified_by_id;
        //     $purchase_requisition->current_status = 'Verified';
        //     $purchase_requisition->save();
        //     $purchase_requisition_status = new PurchaseRequisitionStatus();
        //     $purchase_requisition_status->purchase_requisition_id = $id;
        //     $purchase_requisition_status->status = 'Verified';
        //     $purchase_requisition_status->approved_by = $request->approved_by;
        //     $user_dept = User::find($request->approved_by);
        //     $purchase_requisition_status->department_id = $user_dept->department_id;
        //     $purchase_requisition_status->date = Carbon::now();
        //     $purchase_requisition_status->save();
        //     NotificationController::Notification('Purchase Requisition', 'Verify ACC', '' . route('purchase_requisition.view', $purchase_requisition->id) . '');
        // }
        if ($verified_by == 'acc') {
            if (!Auth::user()->hasPermissionTo('Purchase Requisition Verify HOD')) {
                return back()->with('custom_errors', 'You don`t have the right permission');
            }
            $purchase_requisition = PurchaseRequisition::find($id);
            $user = User::find($request->approved_by);
            $designation = Designation::find($user->designation);
            if ($designation) {
                $pr_approval_settings = PrApproval::all();
                $flag = true;
                foreach ($pr_approval_settings as $pr_approval_setting) {
                    if ($pr_approval_setting->designation_id == $designation[0]->id) {
                        if ($pr_approval_setting->amount >= $purchase_requisition->total && $pr_approval_setting->category == $purchase_requisition->category) {
                            $verified_by_id = Auth::user()->id;
                            $purchase_requisition->verified_by = $verified_by;
                            $purchase_requisition->current_status = 'Verified';
                            $purchase_requisition->verified_by_id = $verified_by_id;
                            $purchase_requisition->save();
                            $purchase_requisition_status = new PurchaseRequisitionStatus();
                            $purchase_requisition_status->purchase_requisition_id = $id;
                            $purchase_requisition_status->status = 'Verified';
                            $purchase_requisition_status->approved_by = $request->approved_by;
                            $purchase_requisition_status->department_id = $user->department_id;
                            $purchase_requisition_status->date = Carbon::now();
                            $purchase_requisition_status->save();
                            $flag = true;
                        } else {
                            $flag = false;
                            //return back()->with('custom_errors', 'Amount is less than the Approver limit or Category is not matched with Approver Category');
                        }
                    } else {
                        $flag = false;
                    }
                }
               
            } else {
                return back()->with('custom_errors', 'Designation not found on Approver');
            }
            if (!$flag) {
                return back()->with('custom_errors', 'Amount is less than the Approver limit or Category is not matched with Approver Category');
            }
        }
        return redirect()->route('purchase_requisition.index')->with('custom_success', 'Purchase Requisition Approved Successfully.');
    }

    public function approve(Request $request, $id)
    {
        if (!Auth::user()->hasPermissionTo('Purchase Requisition Approve')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $purchase_requisition = PurchaseRequisition::find($id);
        $purchase_requisition->current_status = 'Approved';
        $purchase_requisition_status = new PurchaseRequisitionStatus();
        $purchase_requisition_status->purchase_requisition_id = $id;
        $purchase_requisition_status->status = 'Approved';
        $purchase_requisition_status->approved_by = $request->approved_by;
        $user_dept = User::find($request->approved_by);
        $purchase_requisition_status->department_id = $user_dept->department_id;
        $purchase_requisition_status->date = Carbon::now();
        $purchase_requisition_status->save();
        $purchase_requisition->save();

        NotificationController::Notification('Purchase Requisition', 'Approve', '' . route('purchase_requisition.view', $purchase_requisition->id) . '');

        return redirect()->route('purchase_requisition.index')->with('custom_success', 'Purchase Requisition Approved Successfully.');
    }

    public function decline(Request $request, $id, $verified_by)
    {
        if (!Auth::user()->hasPermissionTo('Purchase Requisition Decline')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $verified_by_id = Auth::user()->id;
        $purchase_requisition = PurchaseRequisition::find($id);
        $purchase_requisition->current_status = 'Declined';
        $purchase_requisition->verified_by = $verified_by;
        $purchase_requisition->verified_by_id = $verified_by_id;
        $purchase_requisition_status = new PurchaseRequisitionStatus();
        $purchase_requisition_status->purchase_requisition_id = $id;
        $purchase_requisition_status->status = 'Declined';
        $purchase_requisition_status->approved_by = $request->approved_by;
        $user_dept = User::find($request->approved_by);
        $purchase_requisition_status->department_id = $user_dept->department_id;
        $purchase_requisition_status->reason = $request->reason;
        $purchase_requisition_status->date = Carbon::now();
        $purchase_requisition_status->save();
        $purchase_requisition->save();


        return redirect()->route('purchase_requisition.index')->with('custom_success', 'Purchase Requisition Declined Successfully.');
    }

    public function cancel(Request $request, $id)
    {
        if (!Auth::user()->hasPermissionTo('Purchase Requisition Cancel')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $purchase_requisition = PurchaseRequisition::find($id);
        $purchase_requisition->current_status = 'Cancelled';
        $purchase_requisition_status = new PurchaseRequisitionStatus();
        $purchase_requisition_status->purchase_requisition_id = $id;
        $purchase_requisition_status->status = 'Cancelled';
        $purchase_requisition_status->approved_by = $request->approved_by;
        $user_dept = User::find($request->approved_by);
        $purchase_requisition_status->department_id = $user_dept->department_id;
        $purchase_requisition_status->reason = $request->reason;
        $purchase_requisition_status->date = Carbon::now();
        $purchase_requisition_status->save();
        $purchase_requisition->save();
        return redirect()->route('purchase_requisition.index')->with('custom_success', 'Purchase Requisition Cancelled Successfully.');
    }
}
