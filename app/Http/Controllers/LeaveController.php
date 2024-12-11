<?php

namespace App\Http\Controllers;

use App\Models\leave;
use App\Models\LeaveVerification;
use App\Models\MoreUser;
use App\Models\PersonalUser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class LeaveController extends Controller
{
  public function Data(Request $request)
  {

    if ($request->ajax()) {

      $query = leave::select(
        'leaves.id',
        'leaves.name',
        'leaves.created_at',
        'leaves.entitlement',
        'leaves.status',

      );

      // dd($request->all());

            $datatable = DataTables::eloquent($query)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                  $userRoles = json_decode(Auth::user()->role_ids, true);
                  $isRoleOne = in_array(1, $userRoles);
                  // dd($row->status);
                    $btn = '<div class="d-flex">';
                    if($row->status != "Approved" && $row->status != "Declined"){
                      $btn .= '<a class="btn btn-info btn-sm mx-2" href="' .
                    route('leave.edit', $row->id) .
                    '"><i class="bi bi-pencil"></i></a>';
                    }
                    $btn .= '<a class="btn btn-success btn-sm mx-2" href="' .
                    route('leave.view', $row->id) .
                    '"><i class="bi bi-eye"></i></a>
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
                                                <form method="POST" action="' . route('leave.destroy', $row->id) . '">
                                                    ' . csrf_field() . '
                                                    ' . method_field('DELETE') . '
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div></div>';
                    if ($row->status == 'Request' && $isRoleOne) {
                        $btn .= '<a class="btn btn-warning btn-icon btn-sm mx-2" href="' . route('leave.manage', $row->id) . '" title="Manage">
                            <i class="bi bi-gear"></i>
                        </a>';
                    } 
                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['action']);
                // dd($request->search['value']);




      if ($request->search['value'] == null) {

        $datatable = $datatable->filter(function ($query) use ($request) {
          if ($request->has('user_name') && !is_null($request->user_name)) {
            $query->where('name', 'like', "%{$request->name}%");
          }
          if ($request->has('date') && !is_null($request->date)) {
            $query->where('created_at', 'like', "%{$request->created_at}%");
          }
          if ($request->has('entitlement') && !is_null($request->entitlement)) {
            $query->where('entitlement', 'like', "%{$request->entitlement}%");
          }
          // if ($request->has('status') && !is_null($request->status)) {
          //     $query->where('status', 'like', "%{$request->status}%");
          // }

        });
      }

      return $datatable->make(true);
    }
  }
  public function index()
  {
    if (
      Auth::user()->hasPermissionTo('Leave List') ||
      Auth::user()->hasPermissionTo('Leave Create') ||
      Auth::user()->hasPermissionTo('Leave Edit') ||
      Auth::user()->hasPermissionTo('Leave View') ||
      Auth::user()->hasPermissionTo('Leave Delete')
    ) {
      $leave = Leave::all();
      // dd($leave);
      return view('hr.leave.index', compact('leave'));
    }
    return back()->with('custom_errors', 'You don`t have the right permission');
  }

  public function create()
  {
    if (!Auth::user()->hasPermissionTo('User Create')) {
      return back()->with('custom_errors', 'You don`t have the right permission');
    }
    $userId = Auth::id();
    $baseSalary = PersonalUser::where('id', $userId)->value('base_salary');
    return view('hr.leave.create', compact('baseSalary'));
  }

  public function store(Request $request)
  {
    if (!Auth::user()->hasPermissionTo('Leave Create')) {
      return back()->with('custom_errors', 'You don`t have the right permission');
    }
    $validated = $request->validate([
      'name' => 'required',
      'entitlement' => 'required',
      'from_date' => 'required',
      'to_date' => 'required',
      'from_time' => $request->session ? 'nullable' : 'required',
      'to_time' => $request->session ? 'nullable' : 'required',
    ]);


    // dd($request);

    // dd($request->file('attachment'));
    $leave = new leave();
    $leave->name = $request->name;
    $leave->entitlement = $request->entitlement;
    $leave->status = "Request";
    $leave->balance_day = $request->balance_day;
    $leave->from_date = $request->from_date;
    $leave->to_date = $request->to_date;
    $leave->from_time = $request->from_time ?? null;
    $leave->to_time = $request->to_time ?? null;
    $leave->day = $request->day;
    $leave->session = $request->session;
    $leave->reason = $request->reason;
    // $leave->attachment = $request->attachment;
    // dd($request->attachment);
    if ($request->file('attachment')) {
      $file = $request->file('attachment');
      $filename = date('YmdHis') . $file->getClientOriginalName();
      $file->move('leave-attachments', $filename);
      $leave->attachment =  $filename;
    }
    $leave->emergency = $request->emergency == null ? 0 : 1;
    $leave->save();

    return redirect()->route('leave.index')->with('custom_success', 'Leave Created Successfully.');
  }

  public function edit($id)
  {
    if (!Auth::user()->hasPermissionTo('Leave Edit')) {
      return back()->with('custom_errors', 'You don`t have the right permission');
    }
    $leave = Leave::find($id);
    // dd($leave);
    return view('hr.leave.edit', compact('leave'));
  }

  public function view($id)
  {
    if (!Auth::user()->hasPermissionTo('Leave View')) {
      return back()->with('custom_errors', 'You don`t have the right permission');
    }
    $leave = Leave::find($id);
    $leave_verifications = LeaveVerification::where('leave_id', $id)->get();
    return view('hr.leave.view', compact('leave', 'leave_verifications'));
  }
  public function manage($id)
  {
    if (!Auth::user()->hasPermissionTo('Leave Manage')) {
      return back()->with('custom_errors', 'You don`t have the right permission');
    }
    $leave = Leave::find($id);
    // dd($leave);
    return view('hr.leave.manage', compact('leave'));
  }

  public function manageStore(Request $request, $id)
  {
    if (!Auth::user()->hasPermissionTo('Leave Edit')) {
      return back()->with('custom_errors', 'You don`t have the right permission');
    }
    $validated = $request->validate([
      'status' => [
        'required'
      ],
    ]);


    // dd($request);

    $leave = leave::find($id);

    $leave->status = $request->status;
    $leave->emergency = $request->emergency;
    $leave->pic_remarks = $request->pic_remarks;
    $leave->save();

    return redirect()->route('leave.index')->with('custom_success', 'Leave Managed Successfully.');
  }

  public function update(Request $request, $id)
  {
    if (!Auth::user()->hasPermissionTo('Leave Edit')) {
      return back()->with('custom_errors', 'You don`t have the right permission');
    }
    $validated = $request->validate([
      'name' => 'required',
      'entitlement' => 'required',
      'from_date' => 'required',
      'to_date' => 'required',
      'from_time' => $request->session ? 'nullable' : 'required',
      'to_time' => $request->session ? 'nullable' : 'required',
    ]);


    // dd($request);

    $leave = leave::find($id);
    $leave->name = $request->name;
    $leave->entitlement = $request->entitlement;
    $leave->status = "Request";
    $leave->balance_day = $request->balance_day;
    $leave->from_date = $request->from_date;
    $leave->to_date = $request->to_date;
    $leave->from_time = $request->from_time;
    $leave->to_time = $request->to_time;
    $leave->day = $request->day;
    $leave->session = $request->session;
    $leave->reason = $request->reason;

    if ($request->file('attachment')) {
      $file = $request->file('attachment');
      $filename = date('YmdHis') . $file->getClientOriginalName();
      $file->move('leave-attachments', $filename);
      $leave->attachment =  $filename;
    }
    // dd($leave->attachment);

    // $leave->attachment = $request->attachment;
    $leave->emergency = $request->emergency == null ? 0 : 1;
    $leave->save();

    return redirect()->route('leave.index')->with('custom_success', 'Leave Edited Successfully.');
  }

      public function verify(Request $request, $id){
        if (!Auth::user()->hasPermissionTo('Leave Manage')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $leave= leave::find($id);
        $leave->status = $request->status;
        $leave->emergency = $request->emergency;
        $leave->pic_remarks = $request->pic_remarks;
        $leave->save();

        $more_user = MoreUser::where('user_id',Auth::user()->id)->first();
        if (isset($more_user) && $more_user !== null) {
          if ($leave->entitlement == "Annual") {
              $more_user->annual_leave_balance_day = $more_user->annual_leave_balance_day !== null
                  ? (int)$more_user->annual_leave_balance_day - (int)$leave->day
                  : null;
          } elseif ($leave->entitlement == "Carried Annual") {
              $more_user->carried_leave_balance_day = $more_user->carried_leave_balance_day !== null
                  ? (int)$more_user->carried_leave_balance_day - (int)$leave->day
                  : null;
          } elseif ($leave->entitlement == "Medical") {
              $more_user->medical_leave_balance_day = $more_user->medical_leave_balance_day !== null
                  ? (int)$more_user->medical_leave_balance_day - (int)$leave->day
                  : null;
          } elseif ($leave->entitlement == "Unpaid") {
              $more_user->unpaid_leave_balance_day = $more_user->unpaid_leave_balance_day !== null
                  ? (int)$more_user->unpaid_leave_balance_day - (int)$leave->day
                  : null;
          }
          $more_user->save();
      }

        $leave_verification = new LeaveVerification();
        $leave_verification->leave_id = $id;
        $leave_verification->status = $request->status;
        $leave_verification->date = Carbon::now();
        $leave_verification->approved_by = $request->approved_by;
        $leave_verification->department_id = $request->department_id;
        $leave_verification->designation_id = $request->designation_id;
        $leave_verification->save();
        return redirect()->route('leave.index')->with('custom_success', 'Leave Status Updated Successfully.');
    }

    public function decline(Request $request, $id){
        if (!Auth::user()->hasPermissionTo('Leave Manage')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $leave= leave::find($id);
        $leave->status = $request->status;
        $leave->emergency = $request->emergency;
        $leave->pic_remarks = $request->pic_remarks;
        $leave->save();
        $leave_verification = new LeaveVerification();
        $leave_verification->leave_id = $id;
        $leave_verification->status = $request->status;
        $leave_verification->date = Carbon::now();
        $leave_verification->approved_by = $request->approved_by;
        $leave_verification->department_id = $request->department_id;
        $leave_verification->designation_id = $request->designation_id;
        $leave_verification->save();
        return redirect()->route('leave.index')->with('custom_success', 'Leave Status Updated Successfully.');
    }

  public function destroy(Request $request, $id)
  {
    if (!Auth::user()->hasPermissionTo('Invoice Delete')) {
      return back()->with('custom_errors', 'You don`t have the right permission');
    }
    $leave = Leave::find($id);
    $leave->delete();
    return redirect()->route('leave.index')->with('custom_success', 'Leave Deleted Successfully.');
  }
}
