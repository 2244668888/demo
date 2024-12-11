<?php

namespace App\Http\Controllers;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Helper;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{

    public function Data(Request $request)
    {

        if ($request->ajax()) {

            $query = Role::select(
                'roles.id',
                'roles.name',

            );

// dd($request->all());

            $datatable = DataTables::eloquent($query)
                ->addIndexColumn()

                ->addColumn('action', function($row){
                    $btn = '<div class="d-flex"><a class="btn btn-info btn-sm mx-2" href="' .
                    route('role.edit', $row->id) .
                    '"><i class="bi bi-pencil"></i></a>
                    <a class="btn btn-success btn-sm mx-2" href="' .
                    route('role.view', $row->id) .
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
                                                <form method="POST" action="' . route('role.destroy', $row->id) . '">
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
                    if ($request->has('name') && !is_null($request->name)) {
                        $query->where('name', 'like', "%{$request->name}%");
                    }


                });
            }

               return $datatable->make(true);
        }



    }
    public function index(){
        if (
            Auth::user()->hasPermissionTo('Role List') ||
            Auth::user()->hasPermissionTo('Role Create') ||
            Auth::user()->hasPermissionTo('Role Edit') ||
            Auth::user()->hasPermissionTo('Role View') ||
            Auth::user()->hasPermissionTo('Role Delete')
        ){
            $roles = Role::all();
                return view('administration.role.index', compact('roles'));
            }
        return back()->with('custom_errors', 'You don`t have the right permission');
        }
    public function create(){
        if (!Auth::user()->hasPermissionTo('Role Create')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $permissions = Permission::all();
        $bd = Helper::getpermissions('bd');
        $pvd = Helper::getpermissions('pvd');
        $dashboard = Helper::getpermissions('dashboard');
        $engineering = Helper::getpermissions('engineering');
        $ppc = Helper::getpermissions('ppc');
        $production = Helper::getpermissions('production');
        $oee = Helper::getpermissions('oee');
        $wms_dashboard = Helper::getpermissions('wms_dashboard');
        $operation = Helper::getpermissions('operation');
        $report = Helper::getpermissions('report');
        $hr = Helper::getpermissions('hr');
        $administration = Helper::getpermissions('administration');
        $database = Helper::getpermissions('database');
        $general_setting = Helper::getpermissions('general_setting');
        $accounting = Helper::getpermissions('accounting');
        return view('administration.role.create', compact('permissions', 'hr', 'bd', 'pvd', 'dashboard', 'engineering', 'ppc', 'production', 'oee', 'wms_dashboard', 'operation', 'report', 'administration', 'database', 'general_setting', 'accounting'));
    }

    public function store(Request $request)
    {
        if (!Auth::user()->hasPermissionTo('Role Create')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }

        $validated = $request->validate([
            'name' => [
                'required',
                Rule::unique('roles', 'name')->ignore($request->id),
            ],
            'permissions' => 'required',
        ]);

        $role = Role::create([
            'name' => $request->name
        ]);

        $permissions = $request->input('permissions');
        $permissionNames = Permission::whereIn('id', $permissions)->pluck('name')->toArray();
        $role->syncPermissions($permissionNames);

        return redirect()->route('role.index')->with('custom_success', 'Role Created Successfully');
    }

    public function edit($id)
    {
        if (!Auth::user()->hasPermissionTo('Role Edit')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $role = Role::find($id);
        $permissions = Permission::all();
        $bd = Helper::getpermissions('bd');
        $pvd = Helper::getpermissions('pvd');
        $dashboard = Helper::getpermissions('dashboard');
        $engineering = Helper::getpermissions('engineering');
        $ppc = Helper::getpermissions('ppc');
        $production = Helper::getpermissions('production');
        $oee = Helper::getpermissions('oee');
        $wms_dashboard = Helper::getpermissions('wms_dashboard');
        $operation = Helper::getpermissions('operation');
        $report = Helper::getpermissions('report');
        $hr = Helper::getpermissions('hr');
        $administration = Helper::getpermissions('administration');
        $database = Helper::getpermissions('database');
        $general_setting = Helper::getpermissions('general_setting');
        $accounting = Helper::getpermissions('accounting');
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id", $id)->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')->all();
        return view('administration.role.edit', compact('role', 'permissions', 'hr', 'rolePermissions', 'bd', 'pvd', 'dashboard', 'engineering', 'ppc', 'production', 'oee', 'wms_dashboard', 'operation', 'report', 'administration', 'database', 'general_setting', 'accounting'));
    }

    public function update(Request $request, $id)
    {
        if (!Auth::user()->hasPermissionTo('Role Edit')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }

        $validated = $request->validate([
            'name' => [
                'required',
                Rule::unique('roles', 'name')->ignore($id),
            ],
            'permissions' => 'required',
        ]);

        $role = Role::find($id);
        $role->update([
            'name' => $request->name
        ]);

        $permissions = $request->input('permissions');
        $permissionNames = Permission::whereIn('id', $permissions)->pluck('id','id')->all();
        $role->syncPermissions($permissionNames);
        return redirect()->route('role.index')->with('custom_success', 'Role Updated Successfully');
    }

    public function view($id)
    {
      if (!Auth::user()->hasPermissionTo('Role View')) {
        return back()->with('custom_errors', 'You don`t have the right permission');
    }
        $role = Role::find($id);
        $permissions = Permission::all();
        $bd = Helper::getpermissions('bd');
        $pvd = Helper::getpermissions('pvd');
        $dashboard = Helper::getpermissions('dashboard');
        $engineering = Helper::getpermissions('engineering');
        $ppc = Helper::getpermissions('ppc');
        $production = Helper::getpermissions('production');
        $oee = Helper::getpermissions('oee');
        $wms_dashboard = Helper::getpermissions('wms_dashboard');
        $operation = Helper::getpermissions('operation');
        $report = Helper::getpermissions('report');
        $hr = Helper::getpermissions('hr');
        $administration = Helper::getpermissions('administration');
        $database = Helper::getpermissions('database');
        $accounts_reports = Helper::getpermissions('accounts_reports');
        $general_setting = Helper::getpermissions('general_setting');
        $accounting = Helper::getpermissions('accounting');
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id", $id)->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')->all();
        return view('administration.role.view', compact('role', 'permissions', 'accounts_reports','hr', 'rolePermissions', 'bd', 'pvd', 'dashboard', 'engineering', 'ppc', 'production', 'oee', 'wms_dashboard', 'operation', 'report', 'administration', 'database', 'general_setting', 'accounting'));
}

    public function destroy($id)
    {
        if (!Auth::user()->hasPermissionTo('Role Delete')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $role = Role::find($id);
        $existrole = User::whereJsonContains('role_ids', $id)->exists();
        if ($role->id == Auth::user()->roles->pluck('id')[0]) {
            return back()->with('custom_errors', 'This role has been assigned to you. You cannot delete it. Ask super admin to do that.');
        }
        if ($existrole) {
            return back()->with('custom_errors', 'This role has been assigned to someone. You cannot delete it. First Unassign role from user registration');
        }
        $role->delete();
        return redirect()->route('role.index')->with('custom_success', 'Role Deleted Successfully');
    }
}
