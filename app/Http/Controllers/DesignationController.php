<?php

namespace App\Http\Controllers;
use App\Models\Designation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Helpers\Helper;
use Yajra\DataTables\Facades\DataTables;

class DesignationController extends Controller
{
    //

    public function Data(Request $request)
    {
        if ($request->ajax()) {

            $query = Designation::select(
                'designations.id',
                'designations.name',

            );

// dd($request->all());

            $datatable = DataTables::eloquent($query)
                ->addIndexColumn()

                ->addColumn('action', function($row){
                    $btn = '<div class="d-flex"><a class="btn btn-info btn-sm mx-2" href="' .
                    route('designation.edit', $row->id) .
                    '"><i class="bi bi-pencil"></i></a>
                    <a class="btn btn-success btn-sm mx-2" href="' .
                    route('designation.view', $row->id) .
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
                                                <form method="POST" action="' . route('designation.destroy', $row->id) . '">
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
        Auth::user()->hasPermissionTo('Designation List') ||
        Auth::user()->hasPermissionTo('Designation Create') ||
        Auth::user()->hasPermissionTo('Designation Edit') ||
        Auth::user()->hasPermissionTo('Designation View') ||
        Auth::user()->hasPermissionTo('Designation Delete')
    ) {
      $designations = Designation::all();
      return view('administration.designation.index', compact('designations'));
    }
    return back()->with('custom_errors', 'You don`t have the right permission');
  }
    public function create()
    {
      if (!Auth::user()->hasPermissionTo('Designation Create')) {
        return back()->with('custom_errors', 'You don`t have the right permission');
    }
      return view('administration.designation.create');
    }

    public function store(Request $request)
    {
      if (!Auth::user()->hasPermissionTo('Designation Create')) {
        return back()->with('custom_errors', 'You don`t have the right permission');
    }
        $validated = $request->validate([
            'name' => [
                'required',
                Rule::unique('designations', 'name')->whereNull('deleted_at')
            ],
          ]);

      $designation= new Designation();
      $designation->name = $request->name;
      $designation->save();
      return redirect()->route('designation.index')->with('success', 'designation Created Successfully.');
    }

    public function edit($id)
    {
      if (!Auth::user()->hasPermissionTo('Designation Edit')) {
        return back()->with('custom_errors', 'You don`t have the right permission');
    }
      $designation = Designation::find($id);
      return view('administration.designation.edit', compact('designation'));
    }

    public function update(Request $request, $id)
    {
      if (!Auth::user()->hasPermissionTo('Designation Edit')) {
        return back()->with('custom_errors', 'You don`t have the right permission');
    }
        $validated = $request->validate([
            'name' => [
                'required',
                Rule::unique('designations', 'name')->whereNull('deleted_at')->ignore($id)
            ],
          ]);
      $designation = Designation::find($id);
      $designation->name= $request->name;
      $designation->save();
      return redirect()->route('designation.index')->with('success', 'designation Updated Successfully.');
    }

    public function view($id)
    {
      if (!Auth::user()->hasPermissionTo('Designation View')) {
        return back()->with('custom_errors', 'You don`t have the right permission');
    }
      $designation = Designation::find($id);
      return view('administration.designation.view', compact('designation'));
    }

    public function destroy($id)
    {
      if (!Auth::user()->hasPermissionTo('Designation Delete')) {
        return back()->with('custom_errors', 'You don`t have the right permission');
    }
      $designations = Designation::find($id);
      $designations->delete();
      return redirect()->route('designation.index')->with('success', 'designation Deleted Successfully.');
    }
}
