<?php

namespace App\Http\Controllers;
use App\Models\User;
use Spatie\Permission\Models\Role;
use App\Models\Department;
use App\Models\Designation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Helper;
use App\Models\FamilyChildUser;
use App\Models\FamilyUser;
use App\Models\MoreUser;
use App\Models\UserBankDetail;
use App\Models\PersonalUser;
use App\Models\PayrollDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{



    public function index(){
        if (
          Auth::user()->hasPermissionTo('User List') ||
          Auth::user()->hasPermissionTo('User Create') ||
          Auth::user()->hasPermissionTo('User Edit') ||
          Auth::user()->hasPermissionTo('User View') ||
          Auth::user()->hasPermissionTo('User Delete')
          ){
              $users = User::all();
              return view('administration.user.index', compact('users'));
          }
          return back()->with('custom_errors', 'You don`t have the right permission');
      }


      public function Data(Request $request)
      {


          if ($request->ajax()) {

              $query = User::select(
                  'users.id',
                  'users.code',
                  'users.user_name',
                  'users.full_name',
                  'users.email',
                  'users.department_id',
                  'users.is_active',
                  )->with('department');


  // dd($request->all());

              $datatable = DataTables::eloquent($query)
                  ->addIndexColumn()

                  ->addColumn('action', function($row){
                      $btn = '<div class="d-flex"><a class="btn btn-info btn-sm mx-2" href="' .
                      route('user.edit', $row->id) .
                      '"><i class="bi bi-pencil"></i></a>
                      <a class="btn btn-success btn-sm mx-2" href="' .
                      route('user.view', $row->id) .
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
                                                <form method="POST" action="' . route('user.destroy', $row->id) . '">
                                                    ' . csrf_field() . '
                                                    ' . method_field('DELETE') . '
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div></div>';
                      return $btn;
                  })
                  ->rawColumns(['action']);
                  // dd($request->search['value']);




                  if($request->search['value'] == null ){

                      $datatable = $datatable->filter(function ($query) use ($request) {
                      if ($request->has('code') && !is_null($request->code)) {
                          $query->where('code', 'like', "%{$request->code}%");
                      }
                      if ($request->has('user_name') && !is_null($request->user_name)) {
                          $query->where('user_name', 'like', "%{$request->user_name}%");
                      }
                      if ($request->has('full_name') && !is_null($request->full_name)) {
                          $query->where('full_name', 'like', "%{$request->full_name}%");
                      }

                      if ($request->has('email') && !is_null($request->email)) {
                          $query->where('email', 'like', "%{$request->email}%");
                      }


                      if ($request->has(key: 'department') && !is_null($request->department)) {
                          $query->whereHas('department', function($q) use ($request) {
                              $q->where('departments.name', 'like', "%{$request->department}%");
                          });
                      }

                      if ($request->has('active') && !is_null($request->active)) {
                          $query->where('is_active', 'like', "%{$request->is_active}%");
                      }


                  });
              }

                 return $datatable->make(true);
          }


      }
        public function create()
        {
          if (!Auth::user()->hasPermissionTo('User Create')) {
              return back()->with('custom_errors', 'You don`t have the right permission');
          }
          $departments = Department::all();
          $designations = Designation::all();
          $roles = Role::all();
          return view('administration.user.create', compact('departments', 'designations', 'roles'));
        }

        public function store(Request $request)
        {
          if (!Auth::user()->hasPermissionTo('User Create')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
          }
            $validated = $request->validate([
                'full_name' => [
                    'required'
                ],
                'user_name' => [
                    'required',
                    Rule::unique('users', 'user_name')->whereNull('deleted_at')
                ],
                'email' => [
                    'required',
                    Rule::unique('users', 'email')->whereNull('deleted_at')
                ],
                'password' => [
                    'required',
                    'min:8',
                    'regex:/^(?=.*[A-Za-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]+$/',
                ],
                'department' => [
                  'required'
                ],
                  'designation' => [
                  'required'
                ],
                'role' => [
                  'required'
                ],
              ]);

              // dd($request);

          $user= new User();
          $user->code = $request->code ;
          $user->full_name = $request->full_name ;
          $user->user_name = $request->user_name ;
          $user->email = $request->email;
          $user->password = Hash::make($request->password);
          $user->phone = $request->phone;
          $user->department_id = $request->department ;
          $user->designation_id = $request->designation ;
          $user->is_active = $request->is_active == "yes" ? "yes" :"no" ;
          $user->role_ids = json_encode($request->role);
          $user->save();

          $user_id = $user->id;

          $user_personal = new PersonalUser();
          $user_personal->user_id = $user_id;
          $user_personal->gender = $request->gender ;
          $user_personal->marital_status = $request->marital_status ;
          $user_personal->dob = $request->dob ;
          $user_personal->age = $request->age ;
          $user_personal->address = $request->address ;
          $user_personal->ethnic = $request->ethnic ;
          $user_personal->personal_phone = $request->personal_phone ;
          $user_personal->personal_mobile = $request->personal_mobile ;
          $user_personal->nric = $request->nric ;
          $user_personal->nationality = $request->nationality ;
          $user_personal->sosco_no = $request->sosco_no ;
          $user_personal->base_salary = $request->base_salary ;
          $user_personal->epf_no = $request->epf_no ;
          $user_personal->tin = $request->tin ;
          $user_personal->passport = $request->passport ;
          $user_personal->passport_expiry_date = $request->passport_expiry_date ;
          $user_personal->immigration_no = $request->immigration_no ;
          $user_personal->immigration_no_expiry_date = $request->immigration_no_expiry_date ;
          $user_personal->permit_no = $request->permit_no ;
          $user_personal->permit_no_expiry_date = $request->permit_no_expiry_date ;
          $user_personal->save();

          $user_family = new FamilyUser();
          $user_family->user_id = $user_id;
          $user_family->spouse_name = $request->spouse_name ;
          $user_family->family_dob = $request->family_dob ;
          $user_family->family_age = $request->family_age ;
          $user_family->family_address = $request->family_address ;
          $user_family->family_phone = $request->family_phone ;
          $user_family->family_mobile = $request->family_mobile ;
          $user_family->family_nric = $request->family_nric ;
          $user_family->family_passport = $request->family_passport ;
          $user_family->family_passport_expiry_date = $request->family_passport_expiry_date ;
          $user_family->family_immigration_no = $request->family_immigration_no ;
          $user_family->family_immigration_no_expiry_date = $request->family_immigration_no_expiry_date ;
          $user_family->family_permit_no = $request->family_permit_no ;
          $user_family->family_permit_no_expiry_date = $request->family_permit_no_expiry_date ;
          $user_family->children_no = $request->children_no ;
          if($request->file('attachment')){
                $file = $request->file('attachment');
                $filename = date('YmdHis').$file->getClientOriginalName();
                $file->move('family-attachments', $filename);
                $user_family->attachment =  $filename;
            }
          $user_family->save();


          $user_family_id = $user_family->id;
      if(isset($request->family)){
          foreach ($request->family as $row) {
              $user_family_child = new FamilyChildUser();
              $user_family_child->family_id = $user_family_id;
              $user_family_child->name = $row['name'] ;
              $user_family_child->dob = $row['dob'] ;
              $user_family_child->age = $row['age'] ;
              $user_family_child->birth_certificate_no = $row['birth_certificate_no'] ;
              $user_family_child->save();
          }
      }

      $user_bank = new UserBankDetail();
      $user_bank->user_id = $user_id;
      $user_bank->bank = $request->bank;
      $user_bank->account_no = $request->account_no;
      $user_bank->account_type = $request->account_type;
      $user_bank->branch = $request->branch;
      $user_bank->account_status = $request->account_status;
      $user_bank->save();

          $user_more = new MoreUser();
          $user_more->user_id = $user_id;
          $user_more->emergency_contact_name = $request->emergency_contact_name ;
          $user_more->emergency_contact_relationship = $request->emergency_contact_relationship ;
          $user_more->emergency_contact_address = $request->emergency_contact_address ;
          $user_more->emergency_contact_phone_no = $request->emergency_contact_phone_no ;

          $user_more->annual_leave = $request->annual_leave ;
          $user_more->annual_leave_balance_day = $request->annual_leave_balance_day ;
          $user_more->carried_leave = $request->carried_leave ;
          $user_more->carried_leave_balance_day = $request->carried_leave_balance_day ;
          $user_more->medical_leave = $request->medical_leave ;
          $user_more->medical_leave_balance_day = $request->medical_leave_balance_day ;
          $user_more->unpaid_leave = $request->unpaid_leave ;
          $user_more->unpaid_leave_balance_day = $request->unpaid_leave_balance_day ;
          $user_more->save();



        //   $roleNames = Role::whereIn('id', $request->role)->pluck('name')->toArray();
          $user->assignRole($request->role);

          return redirect()->route('user.index')->with('custom_success', 'User Created Successfully.');
        }

        public function edit($id)
        {
          if (!Auth::user()->hasPermissionTo('User Edit')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
          $user = User::find($id);
          $user_personal = PersonalUser::where('user_id',$id)->first();
          // dd();
          $user_family = FamilyUser::where('user_id',$id)->first();
          // dd($user_family);

          if(isset($user_family)){
              $user_family_child = FamilyChildUser::where('family_id',$user_family->id)->get();
          }else{
              $user_family_child = [];
          }
          $user_bank = UserBankDetail::where('user_id',$id)->first();
          $user_more = MoreUser::where('user_id',$id)->first();
          $departments = Department::all();
          $designations = Designation::all();
          $roles = Role::all();

          return view('administration.user.edit', compact('user','user_bank', 'departments', 'designations', 'roles','user_personal','user_family','user_family_child','user_more'));
        }

        public function update(Request $request, $id)
        {
          if (!Auth::user()->hasPermissionTo('User Edit')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
          }
          $validated = $request->validate([
              'full_name' => [
                  'required'
              ],
              'user_name' => [
                  'required',
                  Rule::unique('users', 'user_name')->whereNull('deleted_at')->ignore($id)
              ],
              'email' => [
                  'required',
                  Rule::unique('users', 'email')->whereNull('deleted_at')->ignore($id)
              ],
              'password' => [
                  'required',
                  'min:8',
                  'regex:/^(?=.*[A-Za-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]+$/',
              ],
              'department' => [
                  'required'
              ],
              'designation' => [
                  'required'
              ],
              'role' => [
                  'required'
              ],
          ]);

          // dd($request->is_active);

          $user= User::find($id);
          $user->code = $request->code ;
          $user->full_name = $request->full_name ;
          $user->user_name = $request->user_name ;
          $user->email = $request->email ;
          $user->password = Hash::make($request->password) ;
          $user->phone = $request->phone ;
          $user->department_id = $request->department ;
          $user->designation_id = $request->designation ;
          $user->is_active = $request->is_active == "yes" ? "yes" :"no" ;
          $user->role_ids = json_encode($request->role) ;
          $user->save();

          // IF USER DEACTIVATE IT SHOULD BE REMOVED FROM PAYROLL DETAILS
          if($user->is_active == 'no'){
            $payroll_details = PayrollDetail::join('payrolls', 'payrolls.id', '=', 'payroll_details.payroll_id')
            ->where('payroll_details.user_id', $user->id)
            ->whereIn('payrolls.status', ['Preparing', 'Declined'])
            ->select('payroll_details.*') // Select payroll_details columns
            ->get();
             foreach ($payroll_details as $payroll_detail) {
                $payroll_detail->deleted_at = Carbon::now();
                $payroll_detail->save();
            }
        }else{
            $payroll_details = PayrollDetail::withTrashed()
            ->join('payrolls', 'payrolls.id', '=', 'payroll_details.payroll_id')
            ->whereNotNull('payroll_details.deleted_at')
            ->where('payroll_details.user_id', $user->id)
            ->whereIn('payrolls.status', ['Preparing', 'Declined'])
            ->select('payroll_details.*') // Select payroll_details columns
            ->get();

            // dd($payroll_details);

            foreach ($payroll_details as $payroll_detail) {
                $payroll_detail->deleted_at = null;
                $payroll_detail->save();
            }
        }


          // $user_id = $id;

          $user_personal =PersonalUser::firstOrNew(['user_id' => $id]);
          // if(isset($user_personal)){

          $user_personal->user_id = $id;
          $user_personal->gender = $request->gender ;
          $user_personal->marital_status = $request->marital_status ;
          $user_personal->dob = $request->dob ;
          $user_personal->age = $request->age ;
          $user_personal->address = $request->address ;
          $user_personal->ethnic = $request->ethnic ;
          $user_personal->personal_phone = $request->personal_phone ;
          $user_personal->personal_mobile = $request->personal_mobile ;
          $user_personal->nric = $request->nric ;
          $user_personal->nationality = $request->nationality ;
          $user_personal->sosco_no = $request->sosco_no ;
          $user_personal->base_salary = $request->base_salary ;
          $user_personal->epf_no = $request->epf_no ;
          $user_personal->tin = $request->tin ;
          $user_personal->passport = $request->passport ;
          $user_personal->passport_expiry_date = $request->passport_expiry_date ;
          $user_personal->immigration_no = $request->immigration_no ;
          $user_personal->immigration_no_expiry_date = $request->immigration_no_expiry_date ;
          $user_personal->permit_no = $request->permit_no ;
          $user_personal->permit_no_expiry_date = $request->permit_no_expiry_date ;
          $user_personal->save();
      // }


          $user_family = FamilyUser::firstOrNew(['user_id' => $id]);
          // if(isset($user_family)){
          $user_family->user_id = $id;
          $user_family->spouse_name = $request->spouse_name  ;
          $user_family->family_dob = $request->family_dob  ;
          $user_family->family_age = $request->family_age  ;
          $user_family->family_address = $request->family_address  ;
          $user_family->family_phone = $request->family_phone  ;
          $user_family->family_mobile = $request->family_mobile  ;
          $user_family->family_nric = $request->family_nric  ;
          $user_family->family_passport = $request->family_passport  ;
          $user_family->family_passport_expiry_date = $request->family_passport_expiry_date  ;
          $user_family->family_immigration_no = $request->family_immigration_no  ;
          $user_family->family_immigration_no_expiry_date = $request->family_immigration_no_expiry_date  ;
          $user_family->family_permit_no = $request->family_permit_no  ;
          $user_family->family_permit_no_expiry_date = $request->family_permit_no_expiry_date  ;
          $user_family->children_no = $request->children_no  ;
          if($request->file('attachment')){
            $file = $request->file('attachment');
            $filename = date('YmdHis').$file->getClientOriginalName();
            $file->move('family-attachments', $filename);
            $user_family->attachment =  $filename;
        }
          $user_family->save();
      $user_family_id = $user_family->id;
          // }


          if(isset($request->family)){
              FamilyChildUser::where('family_id', $user_family_id)->delete();
              foreach ($request->family as $row) {
                  $user_family_child = new FamilyChildUser();
                  $user_family_child->family_id = $user_family_id;
                  $user_family_child->name = $row['name'] ;
                  $user_family_child->dob = $row['dob'] ;
                  $user_family_child->age = $row['age'] ;
                  $user_family_child->birth_certificate_no = $row['birth_certificate_no'] ;
                  $user_family_child->save();
              }
          }


          $user_bank =  UserBankDetail::firstOrNew(['user_id' => $id]);
          if(!$user_bank){
            $user_bank = new UserBankDetail();
            $user_bank->user_id = $id;
            $user_bank->bank = $request->bank;
            $user_bank->account_no = $request->account_no;
            $user_bank->account_type = $request->account_type;
            $user_bank->branch = $request->branch;
            $user_bank->account_status = $request->account_status;
            $user_bank->save();
          }else{
            $user_bank->user_id = $id;
            $user_bank->bank = $request->bank;
            $user_bank->account_no = $request->account_no;
            $user_bank->account_type = $request->account_type;
            $user_bank->branch = $request->branch;
            $user_bank->account_status = $request->account_status;
            $user_bank->save();
          }


          $user_more = MoreUser::firstOrNew(['user_id' => $id]);
          // if(isset($user_more)){
          $user_more->emergency_contact_name = $request->emergency_contact_name ;
          $user_more->emergency_contact_relationship = $request->emergency_contact_relationship ;
          $user_more->emergency_contact_address = $request->emergency_contact_address ;
          $user_more->emergency_contact_phone_no = $request->emergency_contact_phone_no ;
              $user_more->user_id = $id;
          $user_more->annual_leave = $request->annual_leave ;
          $user_more->annual_leave_balance_day = $request->annual_leave_balance_day ;
          $user_more->carried_leave = $request->carried_leave ;
          $user_more->carried_leave_balance_day = $request->carried_leave_balance_day ;
          $user_more->medical_leave = $request->medical_leave ;
          $user_more->medical_leave_balance_day = $request->medical_leave_balance_day ;
          $user_more->unpaid_leave = $request->unpaid_leave ;
          $user_more->unpaid_leave_balance_day = $request->unpaid_leave_balance_day ;
          $user_more->save();
          // }


          $roleNames = Role::whereIn('id', $request->role)->pluck('name')->toArray();
          $user->assignRole($roleNames);

          return redirect()->route('user.index')->with('custom_success', 'User Updated Successfully.');
        }

        public function view($id)
        {
          if (!Auth::user()->hasPermissionTo('User View')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
          }
          $user = User::find($id);
          $departments = Department::all();
          $designations = Designation::all();
          $user_personal = PersonalUser::where('user_id',$id)->first();
          $user_family = FamilyUser::where('user_id',$id)->first();
          if(isset($user_family)){
              $user_family_child = FamilyChildUser::where('family_id',$user_family->id)->get();
          }else{
              $user_family_child = [];
          }
          $user_bank = UserBankDetail::where('user_id',$id)->first();
          $user_more = MoreUser::where('user_id',$id)->first();
          $roles = Role::all();
          return view('administration.user.view', compact('user','user_bank', 'departments', 'designations', 'roles','user_personal','user_family','user_family_child','user_more'));
        }

        public function destroy($id)
        {
          if (!Auth::user()->hasPermissionTo('User Delete')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
          }
          $user = User::find($id);
          if ($id == Auth::user()->id) {
              return back()->with('custom_errors', 'You Can`t delete yourself. Ask super admin to do that.');
          }
          $user_personal = PersonalUser::where('user_id',$id)->delete();
          $user_family = FamilyUser::where('user_id',$id)->first();
          $user_bank_detail = UserBankDetail::where('user_id',$id)->first();
          $user_family_child = FamilyChildUser::where('family_id',$user_family->id)->get();
          if(count($user_family_child) > 0 ){
              FamilyChildUser::where('family_id',$user_family->id)->delete();
          }
          $user_family->delete();
          $user_more = MoreUser::where('user_id',$id)->delete();
          $user_bank_detail->delete();

          $user->delete();
          return redirect()->route('user.index')->with('custom_success', 'User Deleted Successfully.');
        }
}
