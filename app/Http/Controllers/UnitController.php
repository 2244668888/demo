<?php

namespace App\Http\Controllers;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class UnitController extends Controller
{
    public function Data(Request $request)
    {
        if ($request->ajax()) {

            $query = Unit::select(
                'units.id',
                'units.name',
                'units.code',

            );


            $datatable = DataTables::eloquent($query)
                ->addIndexColumn()

                ->addColumn('action', function($row){
                    $btn = '<div class="d-flex"><a class="btn btn-info btn-sm mx-2" href="' .
                    route('unit.edit', $row->id) .
                    '"><i class="bi bi-pencil"></i></a>
                    <a class="btn btn-success btn-sm mx-2" href="' .
                    route('unit.view', $row->id) .
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
                                                <form method="POST" action="' . route('unit.destroy', $row->id) . '">
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
                    if ($request->has('code') && !is_null($request->code)) {
                        $query->where('code', 'like', "%{$request->code}%");
                    }

                });
            }

               return $datatable->make(true);
        }


    }
    public function index()
    {


        if (
            Auth::user()->hasPermissionTo('Unit List') ||
            Auth::user()->hasPermissionTo('Unit Create') ||
            Auth::user()->hasPermissionTo('Unit Edit') ||
            Auth::user()->hasPermissionTo('Unit View') ||
            Auth::user()->hasPermissionTo('Unit Delete')
        ){
            $units = Unit::all();
            return view('database.unit.index', compact('units'));
        }
        return back()->with('custom_errors', 'You don`t have the right permission');
  }
    public function create()
    {
      if (!Auth::user()->hasPermissionTo('Unit Create')) {
        return back()->with('custom_errors', 'You don`t have the right permission');
    }
      return view('database.unit.create');
    }

    public function store(Request $request)
    {
      if (!Auth::user()->hasPermissionTo('Unit Create')) {
        return back()->with('custom_errors', 'You don`t have the right permission');
    }
        $validated = $request->validate([
            'name' => [
                'required',
                Rule::unique('units', 'name')->whereNull('deleted_at')
            ],
            'code' => [
                'required',
                Rule::unique('units', 'code')->whereNull('deleted_at')
            ],
          ]);

      $unit= new Unit();
      $unit->name = $request->name;
      $unit->code = $request->code;
      $unit->save();
      return redirect()->route('unit.index')->with('custom_success', 'Unit Created Successfully.');
    }

    public function edit($id)
    {
      if (!Auth::user()->hasPermissionTo('Unit Edit')) {
        return back()->with('custom_errors', 'You don`t have the right permission');
    }
      $unit = Unit::find($id);
      return view('database.unit.edit', compact('unit'));
    }

    public function update(Request $request, $id)
    {
        if (!Auth::user()->hasPermissionTo('Unit Edit')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }

        $validated = $request->validate([
            'name' => [
                'required',
                Rule::unique('units', 'name')->whereNull('deleted_at')->ignore($id)
            ],
            'code' => [
                'required',
                Rule::unique('units', 'code')->whereNull('deleted_at')->ignore($id)
            ],
          ]);
        $unit = Unit::find($id);
        $unit->name = $request->name;
        $unit->code = $request->code;
        $unit->save();
        return redirect()->route('unit.index')->with('custom_success', 'Unit Updated Successfully.');
    }

    public function view($id)
    {
      if (!Auth::user()->hasPermissionTo('Unit View')) {
        return back()->with('custom_errors', 'You don`t have the right permission');
      }
      $unit = Unit::find($id);
      return view('database.unit.view', compact('unit'));
    }

    public function destroy($id)
    {
      if (!Auth::user()->hasPermissionTo('Unit Delete')) {
        return back()->with('custom_errors', 'You don`t have the right permission');
      }

      $unit = Unit::find($id);
      $unit->delete();
      return redirect()->route('unit.index')->with('custom_success', 'Unit Deleted Successfully.');
    }
}
