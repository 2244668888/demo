<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use App\Models\PayrollApprove;
use App\Models\PayrollDetail;
use App\Models\PayrollSetup;
use App\Models\Account;
use App\Models\AccountCategory;
use App\Models\Transaction;
use App\Models\User;
use App\Models\PayrollDetailChild;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class PayrollController extends Controller
{
    public function Data(Request $request)
    {
        if(
            Auth::user()->hasPermissionTo('Payroll List') ||
            Auth::user()->hasPermissionTo('Payroll Create')
        ){
            if ($request->ajax()) {

                $query = Payroll::select(
                    'payrolls.id',
                    'payrolls.month',
                    'payrolls.year',
                    'payrolls.date',
                    'payrolls.status',
                    'payrolls.payment_status',
                    )->with(['payrollDetails', 'payments']);;

    // dd($request->all());

                $datatable = DataTables::eloquent($query)
                    ->addIndexColumn()

                    ->addColumn('action', function($row){
                        $totalAmount = $row->payrollDetails->sum('net_salary') ?? 0;
                        $remainingBalance = $totalAmount - $row->payments->sum('paying_amount');
                        if($row->status == 'Preparing'){
                            $btn = '<div class="d-flex"><a title="Edit" class="btn btn-info btn-sm mx-2" href="' .
                            route('payroll.edit', $row->id) .
                            '"><i class="bi bi-pencil"></i></a>
                            <a title="View" class="btn btn-success btn-sm mx-2" href="' .
                            route('payroll.view', $row->id) .
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
                                                <form method="POST" action="' . route('payroll.destroy', $row->id) . '">
                                                    ' . csrf_field() . '
                                                    ' . method_field('DELETE') . '
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div></div>';
                            return $btn;
                        }elseif($row->status == 'Pending'){
                            $btn = '<div class="d-flex">
                                <a title="View" class="btn btn-success btn-sm mx-2" href="' .
                                route('payroll.view', $row->id) .
                                '"><i class="bi bi-eye"></i></a>
                                <a class="btn btn-primary btn-sm" title="Approve"  href="' .
                                route('payroll.approve', $row->id) .
                                '"><i class="bi bi-check2-all"></i></a>
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
                                                <form method="POST" action="' . route('payroll.destroy', $row->id) . '">
                                                    ' . csrf_field() . '
                                                    ' . method_field('DELETE') . '
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div></div>';
                                return $btn;
                            }elseif($row->status == 'Declined'){
                                $btn = '<div class="d-flex"><a title="Edit" class="btn btn-info btn-sm mx-2" href="' .
                                route('payroll.edit', $row->id) .
                                '"><i class="bi bi-pencil"></i></a>
                                <a title="View" class="btn btn-success btn-sm mx-2" href="' .
                                route('payroll.view', $row->id) .
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
                                                <form method="POST" action="' . route('payroll.destroy', $row->id) . '">
                                                    ' . csrf_field() . '
                                                    ' . method_field('DELETE') . '
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div></div>';
                                return $btn;
                            }elseif($row->status == 'Cancelled'){
                                $btn = '<div class="d-flex">
                                <a title="View" class="btn btn-success btn-sm mx-2" href="' .
                                route('payroll.view', $row->id) .
                                '"><i class="bi bi-eye"></i></a>
                               </div>';
                                return $btn;
                            }elseif($row->status == 'Approved' ||$row->status == 'Completed'){
                                $btn = '<div class="d-flex">
                                <a title="View" class="btn btn-success btn-sm mx-2" href="' .
                                route('payroll.view', $row->id) .
                                '"><i class="bi bi-eye"></i></a>';
                                if (in_array($row->payment_status, ['due', 'partially_paid'])) {
                                    $btn .= '<a class="btn btn-primary btn-sm mx-2" href="#" data-bs-toggle="modal" data-bs-target="#addPaymentModal" data-id="' . $row->id . '" data-total-amount="'. $totalAmount .'" data-remaining-balance="' . $remainingBalance . '" title="Add Payment"><i class="bi bi-cash-stack"></i></a>';
                                }
                                $btn .= '<a class="btn btn-secondary btn-sm mx-2" href="#" data-bs-toggle="modal" data-bs-target="#viewPaymentsModal" data-id="' . $row->id . '" title="View Payments"><i class="bi bi-currency-exchange"></i></a></div>';
                                return $btn;
                            }

                    })
                    ->rawColumns(['action']);
                    // dd($request->search['value']);
                    if($request->search['value'] == null ){
                        $datatable = $datatable->filter(function ($query) use ($request) {
                        if ($request->has('year') && !is_null($request->year)) {
                            $query->where('year', 'like', "%{$request->year}%");
                        }
                        if ($request->has('month') && !is_null($request->month)) {
                            $query->where('month', 'like', "%{$request->month}%");
                        }
                        if ($request->has('date') && !is_null($request->date)) {
                            $query->where('date', 'like', "%{$request->date}%");
                        }

                        if ($request->has('status') && !is_null($request->status)) {
                            $query->where('status', 'like', "%{$request->status}%");
                        }
                        if ($request->has('payment_status') && !is_null($request->payment_status)) {
                            $query->where('payment_status', 'like', "%{$request->payment_status}%");
                        }
                    });
                }

                   return $datatable->make(true);
            }
            $accounts = Account::with('category')->get();
            $bankCategory = AccountCategory::where('name', 'bank')->first();
            $bankCategoryId = $bankCategory ? $bankCategory->id : null;
            return view('hr.payroll.index', compact('accounts','bankCategoryId'));

        }
        return back()->with(
            'custom_errors',
            'You don`t have the right permission'
        );
    }

    public function generateStore(Request $request){
        if (!Auth::user()->hasPermissionTo('Payroll Create')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }


        $payroll_check = Payroll::where('year',Carbon::now('Asia/Kuala_Lumpur')->format('Y'))->where('month',Carbon::now('Asia/Kuala_Lumpur')->format('F'))->whereNull('deleted_at')->first();
        if (isset($payroll_check) && !empty($payroll_check)) {
            return redirect()->route('payroll.index')->with('custom_errors', 'Payroll already Created for this '.Carbon::now('Asia/Kuala_Lumpur')->format('F').','.Carbon::now('Asia/Kuala_Lumpur')->format('Y'));
        }


        $payroll = new Payroll();
        $payroll->year = Carbon::now('Asia/Kuala_Lumpur')->format('Y');
        $payroll->month = Carbon::now('Asia/Kuala_Lumpur')->format('F'); // Get full month name
        $payroll->date = Carbon::now('Asia/Kuala_Lumpur')->format('d-m-Y');
        $payroll->status = 'Preparing';
        $payroll->payment_status = 'due';
        // if($request->file('attachment')){
        //     $file = $request->file('attachment');
        //     $filename = date('YmdHis').$file->getClientOriginalName();
        //     $file->move('order-attachments', $filename);
        //     $order->attachment =  $filename;
        // }
        $payroll->save();

        $users = User::where('is_active','yes')->with('personalUser')->get();
        $payroll_setup = PayrollSetup::find(1);


        foreach($users as $user){
            $count = PayrollDetail::count() + 1;
            $payroll_detail = new PayrollDetail();
            $payroll_detail->payroll_id = $payroll->id;
            $payroll_detail->user_id = $user->id;
            $payroll_detail->date = Carbon::now('Asia/Kuala_Lumpur')->format('d-m-Y');
            $payroll_detail->gross_salary = isset($user->personalUser) ? $user->personalUser->base_salary : '0';
            if(isset($user->personalUser->base_salary) && $user->personalUser->base_salary < 5000 ){
                if($payroll_setup->kwsp == 1 && $payroll_setup->kwsp != null){
                    if (isset($user->personalUser->age)) {
                        // dd($user->personalUser->age);
                        $ageParts = explode('-', $user->personalUser->age);
                        $years = (int) filter_var($ageParts[0], FILTER_SANITIZE_NUMBER_INT);
                        $months = (int) filter_var($ageParts[1], FILTER_SANITIZE_NUMBER_INT);
                        $totalMonths = ($years * 12) + $months;
                        // dd($totalMonths);
                        // Check if age is less than 60 years (in months)
                        if ($totalMonths < (60 * 12)) {
                            
                            $epf = ((float)$payroll_setup->kwsp_category_1_employee_per / 100 ) * $user->personalUser->base_salary;
                            $epf_emp = ((float)$payroll_setup->kwsp_category_1_employer_per / 100 ) * $user->personalUser->base_salary;
                        } elseif ($totalMonths > (60 * 12)) {
                            $epf = ((float)$payroll_setup->kwsp_category_3_employee_per / 100 ) * $user->personalUser->base_salary;
                            $epf_emp = ((float)$payroll_setup->kwsp_category_3_employer_per / 100 ) * $user->personalUser->base_salary;
                        }else{
                            $epf = 0;
                            $epf_emp = 0;
                        }
                    }else{
                        $epf = 0;
                        $epf_emp = 0;
                    }
                   
                }else{
                    $epf = 0;
                    $epf_emp = 0;
                }
                if($payroll_setup->socso == 1 && $payroll_setup->socso != null){
                $socso = ((float)$payroll_setup->socso_employee_per / 100 ) * $user->personalUser->base_salary;
                $socso_emp = ((float)$payroll_setup->socso_employer_per / 100 ) * $user->personalUser->base_salary;
                } else{
                    $socso = 0;
                    $socso_emp = 0;
                }

                if($payroll_setup->eis == 1 && $payroll_setup->eis != null){
                $eis = ((float)$payroll_setup->eis_employee_per / 100 ) * $user->personalUser->base_salary;
                $eis_emp = ((float)$payroll_setup->eis_employer_per / 100 ) * $user->personalUser->base_salary;
                }else{
                    $eis = 0;
                    $eis_emp = 0;
                }

                if($payroll_setup->eve_employee == 1 && $payroll_setup->eve_employee != null){
                    $eve_employee = ((float)$payroll_setup->socso_employee_per / 100 ) * $user->personalUser->base_salary;
                    } else{
                        $eve_employee = 0;
                    }
                if($payroll_setup->eve_employer == 1 && $payroll_setup->eve_employer != null){
                    $eve_employer = ((float)$payroll_setup->socso_employer_per / 100 ) * $user->personalUser->base_salary;
                    } else{
                        $eve_employer = 0;
                    }

                $payroll_detail->total_deduction = $epf + $socso + $eis ;
                $payroll_detail->net_salary = ((float)$payroll_detail->gross_salary + (float)$eve_employee) - ($epf + $socso + $eis ) ;
                $payroll_detail->company_contribution = ($epf_emp + $socso_emp + $eis_emp + $eve_employer) ;
            }elseif(isset($user->personalUser->base_salary) && $user->personalUser->base_salary > 5000){
                if($payroll_setup->kwsp == 1 && $payroll_setup->kwsp != null){
                    if (isset($user->personalUser->age)) {
                        $ageParts = explode('-', $user->personalUser->age);
                        $years = (int) filter_var($ageParts[0], FILTER_SANITIZE_NUMBER_INT);
                        $months = (int) filter_var($ageParts[1], FILTER_SANITIZE_NUMBER_INT);
                        $totalMonths = ($years * 12) + $months;
                    
                        // Check if age is less than 60 years (in months)
                        if ($totalMonths < (60 * 12)) {
                            $epf = ((float)$payroll_setup->kwsp_category_2_employee_per / 100 ) *$user->personalUser->base_salary;
                            $epf_emp = ((float)$payroll_setup->kwsp_category_2_employer_per / 100 ) *$user->personalUser->base_salary;
                        } elseif ($totalMonths > (60 * 12)) {
                            $epf = ((float)$payroll_setup->kwsp_category_4_employee_per / 100 ) *$user->personalUser->base_salary;
                            $epf_emp = ((float)$payroll_setup->kwsp_category_4_employer_per / 100 ) *$user->personalUser->base_salary;
                        }else{
                            $epf = 0;
                            $epf_emp = 0;
                        }
                    }else{
                        $epf = 0;
                        $epf_emp = 0;
                    }
                   
                }else{
                    $epf = 0;
                    $epf_emp = 0;
                }
                if($payroll_setup->socso == 1 && $payroll_setup->socso != null){
                $socso = ((float)$payroll_setup->socso_employee_per / 100 ) * $user->personalUser->base_salary;
                $socso_emp = ((float)$payroll_setup->socso_employer_per / 100 ) * $user->personalUser->base_salary;
                } else{
                    $socso = 0;
                    $socso_emp = 0;
                }
                if($payroll_setup->eis == 1 && $payroll_setup->eis != null){
                $eis = ((float)$payroll_setup->eis_employee_per / 100 ) * $user->personalUser->base_salary;
                $eis_emp = ((float)$payroll_setup->eis_employer_per / 100 ) * $user->personalUser->base_salary;
                }else{
                    $eis = 0;
                    $eis_emp = 0;
                }

                if($payroll_setup->eve_employee == 1 && $payroll_setup->eve_employee != null){
                    $eve_employee = ((float)$payroll_setup->socso_employee_per / 100 ) * $user->personalUser->base_salary;
                    } else{
                        $eve_employee = 0;
                    }
                if($payroll_setup->eve_employer == 1 && $payroll_setup->eve_employer != null){
                    $eve_employer = ((float)$payroll_setup->socso_employer_per / 100 ) * $user->personalUser->base_salary;
                    } else{
                        $eve_employer = 0;
                    }

                $payroll_detail->total_deduction = $epf + $socso + $eis ;
                $payroll_detail->net_salary = ((float)$payroll_detail->gross_salary + (float)$eve_employee) - ($epf + $socso + $eis) ;
                $payroll_detail->company_contribution = ($epf_emp + $socso_emp + $eis_emp + $eve_employer) ;
            }else{
                $payroll_detail->total_deduction = 0;
                $payroll_detail->net_salary = 0;
                $payroll_detail->company_contribution = 0;
            }

            $payroll_detail->ref_no = 'SLIP/'.$count.''.carbon::now('Asia/Kuala_Lumpur')->format('Y').'';
            $payroll_detail->created_by = Auth::user()->id;
            $payroll_detail->attachment = '';
            $payroll_detail->remarks = '';
            $payroll_detail->hrdf = (isset($payroll_setup->hrdf) && $payroll_setup->hrdf != null) ? $payroll_setup->hrdf : '0';
            $payroll_detail->kwsp = (isset($payroll_setup->kwsp) && $payroll_setup->kwsp != null) ? $payroll_setup->kwsp : '0';
            $payroll_detail->socso = (isset($payroll_setup->socso) && $payroll_setup->socso != null) ? $payroll_setup->socso : '0';
            $payroll_detail->eis =(isset($payroll_setup->eis) && $payroll_setup->eis != null) ? $payroll_setup->eis : '0';
            $payroll_detail->save();


        }
        return redirect()->route('payroll.index')->with('custom_success', 'Payroll Created Successfully for this'.Carbon::now('Asia/Kuala_Lumpur')->format('F').','.Carbon::now('Asia/Kuala_Lumpur')->format('Y'));
    }

    public function approve($id)
    {
    //   if (!Auth::user()->hasPermissionTo('Process Edit')) {
    //     return back()->with('custom_errors', 'You don`t have the right permission');
    // }
      $payroll = Payroll::find($id);
      return view('hr.payroll.approve', compact('payroll'));
    }

    public function verify(Request $request, $id){
        // if (!Auth::user()->hasPermissionTo('BOM Verify')) {
        //     return back()->with('custom_errors', 'You don`t have the right permission');
        // }
        $payroll = Payroll::find($id);
        $payroll->status = 'Approved';
        $payroll->save();
        $payroll_verification = new PayrollApprove();
        $payroll_verification->payroll_id = $id;
        $payroll_verification->status = 'Approved';
        $payroll_verification->date = Carbon::now();
        $payroll_verification->created_by = Auth::user()->id;
        $payroll_verification->department_id = Auth::user()->department_id;
        $payroll_verification->designation_id = Auth::user()->designation_id;
        $payroll_verification->save();
        return redirect()->route('payroll.index')->with('custom_success', 'Payroll Status Approved Successfullyfor this'.Carbon::now('Asia/Kuala_Lumpur')->format('F').','.Carbon::now('Asia/Kuala_Lumpur')->format('Y'));
    }

    public function decline(Request $request, $id){
        // if (!Auth::user()->hasPermissionTo('BOM Decline')) {
        //     return back()->with('custom_errors', 'You don`t have the right permission');
        // }

        //    echo '<pre>';
        //         print_r($request->toarray());
        //         die();
        //         echo '</pre>';

        $payroll = Payroll::find($id);
        $payroll->status = "Declined";
        $payroll->save();

        $payroll_verification = new PayrollApprove();
        $payroll_verification->payroll_id = $id;
        $payroll_verification->status = "Declined";
        $payroll_verification->date = Carbon::now();
        $payroll_verification->reason = $request->reason ?? "NULL";
        $payroll_verification->created_by = Auth::user()->id;
        $payroll_verification->department_id = Auth::user()->department_id;
        $payroll_verification->designation_id = Auth::user()->designation_id;
        $payroll_verification->save();
        return redirect()->route('payroll.index')->with('custom_success', 'Payroll Status Declined Successfullyfor this'.Carbon::now('Asia/Kuala_Lumpur')->format('F').','.Carbon::now('Asia/Kuala_Lumpur')->format('Y'));
    }

    public function Cancel(Request $request, $id){
        // if (!Auth::user()->hasPermissionTo('BOM Cancel')) {
        //     return back()->with('custom_errors', 'You don`t have the right permission');
        // }

        $payroll = Payroll::find($id);
        $payroll->status = "Cancelled";
        $payroll->save();

        $payroll_verification = new PayrollApprove();
        $payroll_verification->payroll_id = $id;
        $payroll_verification->status = "Cancelled";
        $payroll_verification->date = Carbon::now();
        $payroll_verification->reason = $request->reason ?? "NULL";
        $payroll_verification->created_by = Auth::user()->id;
        $payroll_verification->department_id = Auth::user()->department_id;
        $payroll_verification->designation_id = Auth::user()->designation_id;
        $payroll_verification->save();
        return redirect()->route('payroll.index')->with('custom_success', 'Payroll Status Cancelled Successfullyfor this'.Carbon::now('Asia/Kuala_Lumpur')->format('F').','.Carbon::now('Asia/Kuala_Lumpur')->format('Y'));
    }

    public function edit($id)
    {
    //   if (!Auth::user()->hasPermissionTo('Process Edit')) {
    //     return back()->with('custom_errors', 'You don`t have the right permission');
    // }
      $payroll = Payroll::find($id);
      return view('hr.payroll.edit', compact('payroll'));
    }
    public function view($id)
    {
    //   if (!Auth::user()->hasPermissionTo('Process Edit')) {
    //     return back()->with('custom_errors', 'You don`t have the right permission');
    // }
      $payroll = Payroll::find($id);
      $payroll_verifications = PayrollApprove::where('payroll_id',$id)->with(['created_by_user','department','designation'])->get();
    //    dd($payroll_verifications);
      return view('hr.payroll.view', compact('payroll','payroll_verifications'));
    }


    public function update(Request $request, $id){
        // if (!Auth::user()->hasPermissionTo('Order Edit')) {
        //     return back()->with('custom_errors', 'You don`t have the right permission');
        // }

        $payroll = Payroll::find($id);
        $payroll->status = "Pending";
        $payroll->save();

        return redirect()->route('payroll.index')->with('custom_success', 'Payroll Updated Successfullyfor this'.Carbon::now('Asia/Kuala_Lumpur')->format('F').','.Carbon::now('Asia/Kuala_Lumpur')->format('Y'));
    }

    public function destroy($id){
        //   if (!Auth::user()->hasPermissionTo('Process Edit')) {
    //     return back()->with('custom_errors', 'You don`t have the right permission');
    // }

    $payroll = Payroll::find($id);
        $month = $payroll->month;
        $year = $payroll->year;
        if (!empty($payroll)) {
            // Retrieve all payroll details related to this payroll_id
            $payroll_details = PayrollDetail::where('payroll_id', $id)->get();

            // Loop through each payroll detail to handle its child records and deletion
            if ($payroll_details->isNotEmpty()) {
                foreach ($payroll_details as $payroll_detail) {
                    // Retrieve all child records for each payroll detail
                    $payroll_detail_childs = PayrollDetailChild::where('payroll_detail_id', $payroll_detail->id)->get();

                    // If there are child records, delete them
                    if ($payroll_detail_childs->isNotEmpty()) {
                        PayrollDetailChild::where('payroll_detail_id', $payroll_detail->id)->delete();
                    }

                    // After deleting child records, delete the payroll detail itself
                    $payroll_detail->delete();
                }
            }
        }
        $payroll->delete();

    return redirect()->route('payroll.index')->with('custom_success', 'Payroll Deleted Successfully for this'.$month .', '.$year);
}




    // PAYROLL DETAIL FUNCTIONS


    public function payrollDetailData(Request $request,$id)
    {

        if ($request->ajax()) {
            $query = PayrollDetail::select(
                'payroll_details.id',
                'payroll_details.user_id',
                'payroll_details.net_salary',
                'payroll_details.gross_salary',
                'payroll_details.total_deduction',
                'payroll_details.company_contribution',
                )->where('payroll_id',$id)->with('user');

// dd($request->all());

            $datatable = DataTables::eloquent($query)
                ->addIndexColumn()

                ->addColumn('action', function($row){

                        $btn = '<div class="d-flex"><a title="Edit" class="btn btn-info btn-sm mx-2" href="' .
                        route('payroll_detail.edit', $row->id) .
                        '"><i class="bi bi-pencil"></i></a>
                        <a title="View" class="btn btn-success btn-sm mx-2" href="' .
                        route('payroll_detail.view', $row->id) .
                        '"><i class="bi bi-eye"></i></a>
                        <a class="btn btn-info btn-sm mx-2 " href="' .
                        route('payroll_detail.preview', $row->id) .
                        '"><i class="bi bi-file-pdf"></i></a></div>';
                        return $btn;
                })
                ->rawColumns(['action']);
                // dd($request->search['value']);
                if($request->search['value'] == null ){
                    $datatable = $datatable->filter(function ($query) use ($request) {
                    if ($request->has('code') && !is_null($request->code)) {
                            $query->whereHas('user', function($q) use ($request) {
                                $q->where('users.code', 'like', "%{$request->code}%");
                            });
                    }
                    if ($request->has('user') && !is_null($request->user)) {
                            $query->whereHas('user', function($q) use ($request) {
                                $q->where('users.user_name', 'like', "%{$request->user}%");
                            });
                    }

                    if ($request->has('gross_salary') && !is_null($request->gross_salary)) {
                        $query->where('gross_salary', 'like', "%{$request->gross_salary}%");
                    }
                    if ($request->has('total_deduction') && !is_null($request->total_deduction)) {
                        $query->where('total_deduction', 'like', "%{$request->total_deduction}%");
                    }
                    if ($request->has('net_salary') && !is_null($request->net_salary)) {
                        $query->where('net_salary', 'like', "%{$request->net_salary}%");
                    }

                    if ($request->has('company_contribution') && !is_null($request->company_contribution)) {
                        $query->where('company_contribution', 'like', "%{$request->company_contribution}%");
                    }
                });
            }

               return $datatable->make(true);
        }
    }


    public function payrollDetailDataView(Request $request,$id)
    {

        if ($request->ajax()) {
            $query = PayrollDetail::select(
                'payroll_details.id',
                'payroll_details.user_id',
                'payroll_details.net_salary',
                'payroll_details.gross_salary',
                'payroll_details.total_deduction',
                'payroll_details.company_contribution',
                )->where('payroll_id',$id)->with('user');

// dd($request->all());

            $datatable = DataTables::eloquent($query)
                ->addIndexColumn()

                ->addColumn('action', function($row){

                        $btn = '<div class="d-flex">
                        <a title="View" class="btn btn-success btn-sm mx-2" href="' .
                        route('payroll_detail.view', $row->id) .
                        '"><i class="bi bi-eye"></i></a>
                        <a class="btn btn-info btn-sm mx-2 " href="' .
                        route('payroll_detail.preview', $row->id) .
                        '"><i class="bi bi-file-pdf"></i></a></div>';
                        return $btn;
                })
                ->rawColumns(['action']);
                // dd($request->search['value']);
                if($request->search['value'] == null ){
                    $datatable = $datatable->filter(function ($query) use ($request) {
                    if ($request->has('code') && !is_null($request->code)) {
                            $query->whereHas('user', function($q) use ($request) {
                                $q->where('users.code', 'like', "%{$request->code}%");
                            });
                    }
                    if ($request->has('user') && !is_null($request->user)) {
                            $query->whereHas('user', function($q) use ($request) {
                                $q->where('users.user_name', 'like', "%{$request->user}%");
                            });
                    }

                    if ($request->has('gross_salary') && !is_null($request->gross_salary)) {
                        $query->where('gross_salary', 'like', "%{$request->gross_salary}%");
                    }
                    if ($request->has('total_deduction') && !is_null($request->total_deduction)) {
                        $query->where('total_deduction', 'like', "%{$request->total_deduction}%");
                    }
                    if ($request->has('net_salary') && !is_null($request->net_salary)) {
                        $query->where('net_salary', 'like', "%{$request->net_salary}%");
                    }

                    if ($request->has('company_contribution') && !is_null($request->company_contribution)) {
                        $query->where('company_contribution', 'like', "%{$request->company_contribution}%");
                    }
                });
            }

               return $datatable->make(true);
        }
    }


    public function payrollDetailEdit($id)
    {
    //   if (!Auth::user()->hasPermissionTo('Process Edit')) {
    //     return back()->with('custom_errors', 'You don`t have the right permission');
    // }
      $payroll_detail = PayrollDetail::find($id);
      $payroll = Payroll::find($payroll_detail->payroll_id);
      $payroll_detail_childs = PayrollDetailChild::where('payroll_detail_id',$payroll_detail->id)->get();      return
       view('hr.payroll.payroll_detail.edit', compact('payroll','payroll_detail','payroll_detail_childs'));
    }
    public function payrollDetailView($id)
    {
    //   if (!Auth::user()->hasPermissionTo('Process Edit')) {
    //     return back()->with('custom_errors', 'You don`t have the right permission');
    // }
      $payroll_detail = PayrollDetail::find($id);
      $payroll = Payroll::find($payroll_detail->payroll_id);
      $payroll_detail_childs = PayrollDetailChild::where('payroll_detail_id',$payroll_detail->id)->get();      return
       view('hr.payroll.payroll_detail.view', compact('payroll','payroll_detail','payroll_detail_childs'));
    }

    public function payrollDetailDestroy($id){
        //   if (!Auth::user()->hasPermissionTo('Process Edit')) {
    //     return back()->with('custom_errors', 'You don`t have the right permission');
    // }

    $payroll_detail = PayrollDetail::find($id);
        $payroll_id = $payroll_detail->payroll_id;
    if(!empty($payroll_detail)){
        $payroll_detail_childs = PayrollDetailChild::where('payroll_detail_id',$payroll_detail->id)->get();
        if($payroll_detail_childs->isNotEmpty()){
            PayrollDetailChild::where('payroll_detail_id',$payroll_detail->id)->delete();
        }
        $payroll_detail->delete();
    }
    return redirect()->route('payroll.edit',parameters: $payroll_id)->with('custom_success', 'Payroll Detail Deleted Successfully for '.$payroll_detail->user->user_name);
}

    public function payrollDetailStore(Request $request,$id){
        if (!Auth::user()->hasPermissionTo('Order Create')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        // dd($request->item);
        // $sumOfValues = 0;
        // foreach ($request->item as $index => $subArray) {
        //     $sumOfValues += $subArray['value'];
        // }
        // dd($request->all());
        $payroll_detail =  PayrollDetail::find($id);

        $user = User::where('is_active','yes')->where('id',$request->user_id)->with('personalUser')->first();
        $payroll_setup = PayrollSetup::find(1);

        if ($payroll_setup->eve_ot == '1') {
            if (isset($request->item)) {
               $overtime = $request->item[4]['value'];
               $payroll_detail->gross_salary = isset($user->personalUser) ? $user->personalUser->base_salary + (float)$overtime : '0';
            }else{
                $payroll_detail->gross_salary = isset($user->personalUser) ? $user->personalUser->base_salary : '0';
            }
        }elseif($payroll_setup->eve_ot_allowance == '1'){
            $sumOfValues = 0;
            if (isset($request->item)) {
                foreach ($request->item as $index => $subArray) {
                    $sumOfValues += $subArray['value'];
                }
                $payroll_detail->gross_salary = isset($user->personalUser) ? $user->personalUser->base_salary + $sumOfValues : '0';
                // dd($payroll_detail->gross_salary);
            }else{
                $payroll_detail->gross_salary = isset($user->personalUser) ? $user->personalUser->base_salary : '0';
            }
        }else{
            $payroll_detail->gross_salary = isset($user->personalUser) ? $user->personalUser->base_salary : '0';
        }

            // dd($user);
            // $payroll_detail->gross_salary = isset($user->personalUser) ? $user->personalUser->base_salary + $sumOfValues : '0';
            if(isset($user->personalUser->base_salary) && $user->personalUser->base_salary < 5000 ){
                if($payroll_setup->kwsp == 1 && $payroll_setup->kwsp != null){
                    if (isset($user->personalUser->age)) {
                        // dd($user->personalUser->age);
                        $ageParts = explode('-', $user->personalUser->age);
                        $years = (int) filter_var($ageParts[0], FILTER_SANITIZE_NUMBER_INT);
                        $months = (int) filter_var($ageParts[1], FILTER_SANITIZE_NUMBER_INT);
                        $totalMonths = ($years * 12) + $months;
                        // dd($totalMonths);
                        // Check if age is less than 60 years (in months)
                        if ($totalMonths < (60 * 12)) {
                            
                            $epf = ((float)$payroll_setup->kwsp_category_1_employee_per / 100 ) * $user->personalUser->base_salary;
                            $epf_emp = ((float)$payroll_setup->kwsp_category_1_employer_per / 100 ) * $user->personalUser->base_salary;
                        } elseif ($totalMonths > (60 * 12)) {
                            $epf = ((float)$payroll_setup->kwsp_category_3_employee_per / 100 ) * $user->personalUser->base_salary;
                            $epf_emp = ((float)$payroll_setup->kwsp_category_3_employer_per / 100 ) * $user->personalUser->base_salary;
                        }else{
                            $epf = 0;
                            $epf_emp = 0;
                        }
                    }else{
                        $epf = 0;
                        $epf_emp = 0;
                    }
                   
                }else{
                    $epf = 0;
                    $epf_emp = 0;
                }
                if($payroll_setup->socso == 1 && $payroll_setup->socso != null){
                $socso = ((float)$payroll_setup->socso_employee_per / 100 ) * $user->personalUser->base_salary;
                $socso_emp = ((float)$payroll_setup->socso_employer_per / 100 ) * $user->personalUser->base_salary;
                } else{
                    $socso = 0;
                    $socso_emp = 0;
                }

                if($payroll_setup->eis == 1 && $payroll_setup->eis != null){
                $eis = ((float)$payroll_setup->eis_employee_per / 100 ) * $user->personalUser->base_salary;
                $eis_emp = ((float)$payroll_setup->eis_employer_per / 100 ) * $user->personalUser->base_salary;
                }else{
                    $eis = 0;
                    $eis_emp = 0;
                }

                if($payroll_setup->eve_employee == 1 && $payroll_setup->eve_employee != null){
                    $eve_employee = ((float)$payroll_setup->socso_employee_per / 100 ) * $user->personalUser->base_salary;
                    } else{
                        $eve_employee = 0;
                    }
                if($payroll_setup->eve_employer == 1 && $payroll_setup->eve_employer != null){
                    $eve_employer = ((float)$payroll_setup->socso_employer_per / 100 ) * $user->personalUser->base_salary;
                    } else{
                        $eve_employer = 0;
                    }

                $payroll_detail->total_deduction = $epf + $socso + $eis;
                $payroll_detail->net_salary = ((float)$payroll_detail->gross_salary + $eve_employee) - ($epf + $socso + $eis) ;
                $payroll_detail->company_contribution = ($epf_emp + $socso_emp + $eis_emp + $eve_employer);
            }elseif(isset($user->personalUser->base_salary) && $user->personalUser->base_salary > 5000){
                if($payroll_setup->kwsp == 1 && $payroll_setup->kwsp != null){
                    if (isset($user->personalUser->age)) {
                        $ageParts = explode('-', $user->personalUser->age);
                        $years = (int) filter_var($ageParts[0], FILTER_SANITIZE_NUMBER_INT);
                        $months = (int) filter_var($ageParts[1], FILTER_SANITIZE_NUMBER_INT);
                        $totalMonths = ($years * 12) + $months;
                    
                        // Check if age is less than 60 years (in months)
                        if ($totalMonths < (60 * 12)) {
                            $epf = ((float)$payroll_setup->kwsp_category_2_employee_per / 100 ) *$user->personalUser->base_salary;
                            $epf_emp = ((float)$payroll_setup->kwsp_category_2_employer_per / 100 ) *$user->personalUser->base_salary;
                        } elseif ($totalMonths > (60 * 12)) {
                            $epf = ((float)$payroll_setup->kwsp_category_4_employee_per / 100 ) *$user->personalUser->base_salary;
                            $epf_emp = ((float)$payroll_setup->kwsp_category_4_employer_per / 100 ) *$user->personalUser->base_salary;
                        }else{
                            $epf = 0;
                            $epf_emp = 0;
                        }
                    }else{
                        $epf = 0;
                        $epf_emp = 0;
                    }
                   
                }else{
                    $epf = 0;
                    $epf_emp = 0;
                }
                if($payroll_setup->socso == 1 && $payroll_setup->socso != null){
                $socso = ((float)$payroll_setup->socso_employee_per / 100 ) * $user->personalUser->base_salary;
                $socso_emp = ((float)$payroll_setup->socso_employer_per / 100 ) * $user->personalUser->base_salary;
                } else{
                    $socso = 0;
                    $socso_emp = 0;
                }
                if($payroll_setup->eis == 1 && $payroll_setup->eis != null){
                $eis = ((float)$payroll_setup->eis_employee_per / 100 ) * $user->personalUser->base_salary;
                $eis_emp = ((float)$payroll_setup->eis_employer_per / 100 ) * $user->personalUser->base_salary;
                }else{
                    $eis = 0;
                    $eis_emp = 0;
                }

                if($payroll_setup->eve_employee == 1 && $payroll_setup->eve_employee != null){
                    $eve_employee = ((float)$payroll_setup->socso_employee_per / 100 ) * $user->personalUser->base_salary;
                    } else{
                        $eve_employee = 0;
                    }
                if($payroll_setup->eve_employer == 1 && $payroll_setup->eve_employer != null){
                    $eve_employer = ((float)$payroll_setup->socso_employer_per / 100 ) * $user->personalUser->base_salary;
                    } else{  
                        $eve_employer = 0;
                    }

                $payroll_detail->total_deduction = $epf + $socso + $eis ;
                $payroll_detail->net_salary = ((float)$payroll_detail->gross_salary + $eve_employee) - ($epf + $socso + $eis ) ;
                $payroll_detail->company_contribution = ($epf_emp + $socso_emp + $eis_emp + $eve_employer) ;
            }else{
                $payroll_detail->total_deduction = 0;
                $payroll_detail->net_salary = 0;
                $payroll_detail->company_contribution = 0;
            }

            // $payroll_detail->ref_no = 'SLIP/'.$count.''.carbon::now('Asia/Kuala_Lumpur')->format('Y').'';
            // $payroll_detail->created_by = Auth::user()->id;

           if($request->file('attachment')){
                    $file = $request->file('attachment');
                    $filename = date('YmdHis').$file->getClientOriginalName();
                    $file->move('payroll-detail-attachments', $filename);
                    $payroll_detail->attachment =  $filename;
                }
            $payroll_detail->remarks = $request->remarks;
            $payroll_detail->hrdf = isset($request->hrdf) ? $request->hrdf : '0';
            $payroll_detail->kwsp = isset($request->kwsp) ? $request->kwsp : '0';
            $payroll_detail->socso = isset($request->socso) ? $request->socso : '0';
            $payroll_detail->eis = isset($request->eis) ? $request->eis : '0';
            $payroll_detail->eve_employee = isset($request->eve_employee) ? $request->eve_employee : '0';
            $payroll_detail->eve_ot = isset($request->eve_ot) ? $request->eve_ot : '0';
            $payroll_detail->eve_ot_allowance = isset($request->eve_ot_allowance) ? $request->eve_ot_allowance : '0';
            $payroll_detail->eve_employer = isset($request->eve_employer) ? $request->eve_employer : '0';
            $payroll_detail->save();

            $payroll_detail_child = PayrollDetailChild::where('payroll_detail_id', $payroll_detail->id)->get();
                // dd(vars: $request->item);
            if (isset($payroll_detail_child)) {
                PayrollDetailChild::where('payroll_detail_id', $payroll_detail->id)->delete();
                // dd(PayrollDetailChild::all());
                foreach($request->item as $key => $child){
                    // dd($child);
                    $payroll_detail_child = new PayrollDetailChild();
                    $payroll_detail_child->payroll_detail_id = $payroll_detail->id;
                    $payroll_detail_child->particular = isset($child['particular']) ? $child['particular'] : '' ;
                    $payroll_detail_child->value = isset($child['value']) ? $child['value'] : 0 ;
                    $payroll_detail_child->checkbox = isset($child['checkbox']) ? $child['checkbox'] :0;
                    $payroll_detail_child->save();
                }
            }

        return redirect()->route('payroll.edit',$payroll_detail->payroll_id)->with('custom_success', 'Payroll Detail Updated Successfully for '.$payroll_detail->user->user_name);
    }



    public function preview(Request $request, $id){
        // if (!Auth::user()->hasPermissionTo('Invoice Preview')) {
        //     return back()->with('custom_errors', 'You don`t have the right permission');
        // }
        $payroll_detail = PayrollDetail::with(['user.personalUser','user.user_bank_detail','user.designation','user.department'])->find($id);
        $payroll = Payroll::find($payroll_detail->payroll_id);
        $payroll_setup = PayrollSetup::find(1);
        $payroll_detail_childs = PayrollDetailChild::where('payroll_detail_id',$payroll_detail->id)->get();
        $pdf = FacadePdf::loadView('hr.payroll.payroll_detail.preview', compact('payroll_setup','payroll_detail', 'payroll', 'payroll_detail_childs'))->setPaper('a4');
        return $pdf->stream('payslip.preview');
    }

}
