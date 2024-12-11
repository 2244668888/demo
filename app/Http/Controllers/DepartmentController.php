<?php

namespace App\Http\Controllers;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Helper;
use Yajra\DataTables\Facades\DataTables;

class DepartmentController extends Controller
{
    //

    public function Data(Request $request)
    {
        if ($request->ajax()) {

            $query = Department::select(
                'departments.id',
                'departments.name',

            );

// dd($request->all());

            $datatable = DataTables::eloquent($query)
                ->addIndexColumn()

                ->addColumn('action', function($row){
                    $btn = '<div class="d-flex"><a class="btn btn-info btn-sm mx-2" href="' .
                    route('department.edit', $row->id) .
                    '"><i class="bi bi-pencil"></i></a>
                    <a class="btn btn-success btn-sm mx-2" href="' .
                    route('department.view', $row->id) .
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
                                                <form method="POST" action="' . route('department.destroy', $row->id) . '">
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
        Auth::user()->hasPermissionTo('Department List') ||
        Auth::user()->hasPermissionTo('Department Create') ||
        Auth::user()->hasPermissionTo('Department Edit') ||
        Auth::user()->hasPermissionTo('Department View') ||
        Auth::user()->hasPermissionTo('Department Delete')
    ){
        $departments = Department::all();
        return view('administration.department.index', compact('departments'));
      }
      return back()->with('custom_errors', 'You don`t have the right permission');
    }
      public function create()
      {
        if (!Auth::user()->hasPermissionTo('Department Create')) {
          return back()->with('custom_errors', 'You don`t have the right permission');
      }
        return view('administration.department.create');
      }

      public function store(Request $request)
      {
        if (!Auth::user()->hasPermissionTo('Department Create')) {
          return back()->with('custom_errors', 'You don`t have the right permission');
      }
          $validated = $request->validate([
              'name' => [
                  'required',
                  Rule::unique('departments', 'name')->whereNull('deleted_at')
              ],
            ]);

        $department= new Department();
        $department->name = $request->name;
        $department->save();
        return redirect()->route('department.index')->with('custom_success', 'Department Created Successfully.');
      }

      public function edit($id)
      {
        if (!Auth::user()->hasPermissionTo('Department Edit')) {
          return back()->with('custom_errors', 'You don`t have the right permission');
      }
        $department = Department::find($id);
        return view('administration.department.edit', compact('department'));
      }

      public function update(Request $request, $id)
      {
        if (!Auth::user()->hasPermissionTo('Department Edit')) {
          return back()->with('custom_errors', 'You don`t have the right permission');
      }

          $validated = $request->validate([
              'name' => [
                  'required',
                  Rule::unique('departments', 'name')->whereNull('deleted_at')->ignore($id)
              ],
            ]);
        $department = Department::find($id);
        $department->name= $request->name;
        $department->save();
        return redirect()->route('department.index')->with('custom_success', 'Department Updated Successfully.');
      }

      public function view($id)
      {
        if (!Auth::user()->hasPermissionTo('Department View')) {
          return back()->with('custom_errors', 'You don`t have the right permission');
      }
        $department = Department::find($id);
        return view('administration.department.view', compact('department'));
      }

      public function destroy($id)
      {
        if (!Auth::user()->hasPermissionTo('Department Delete')) {
          return back()->with('custom_errors', 'You don`t have the right permission');
      }

        $departments = Department::find($id);
        $departments->delete();
        return redirect()->route('department.index')->with('custom_success', 'Department Deleted Successfully.');
      }
}
