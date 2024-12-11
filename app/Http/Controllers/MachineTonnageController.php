<?php

namespace App\Http\Controllers;
use App\Models\MachineTonnage;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class MachineTonnageController extends Controller
{


    public function Data(Request $request)
    {
        if ($request->ajax()) {

            $query = MachineTonnage::select(
                'id',
                'tonnage',

            );



    // dd($request->all());

                $datatable = DataTables::eloquent($query)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '<div class="d-flex"><a class="btn btn-info btn-sm mx-2" href="' .
                    route('machine_tonage.edit', $row->id) .
                    '"><i class="bi bi-pencil"></i></a>
                    <a class="btn btn-success btn-sm mx-2" href="' .
                    route('machine_tonage.view', $row->id) .
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
                                                <form method="POST" action="' . route('machine_tonage.destroy', $row->id) . '">
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
                if($request->search['value'] == null ){
                    $datatable = $datatable->filter(function ($query) use ($request) {
                    if ($request->has('tonnage') && !is_null($request->tonnage)) {
                        $query->where('tonnage', 'like', "%{$request->tonnage}%");
                    }


                });
            }
                return $datatable->make(true);
        }
    }

    public function index()
    {


        if (
            Auth::user()->hasPermissionTo('Machine Tonnage List') ||
            Auth::user()->hasPermissionTo('Machine Tonnage Create') ||
            Auth::user()->hasPermissionTo('Machine Tonnage Edit') ||
            Auth::user()->hasPermissionTo('Machine Tonnage View') ||
            Auth::user()->hasPermissionTo('Machine Tonnage Delete')
        ) {
            $machine_tonnages = MachineTonnage::all();
            return view('database.machine_tonnage.index', compact('machine_tonnages'));
        }
        return back()->with('custom_errors', 'You don`t have the right permission');
  }
    public function create()
    {
      if (!Auth::user()->hasPermissionTo('Machine Tonnage Create')) {
        return back()->with('custom_errors', 'You don`t have the right permission');
    }
      return view('database.machine_tonnage.create');
    }

    public function store(Request $request)
    {
      if (!Auth::user()->hasPermissionTo('Machine Tonnage Create')) {
        return back()->with('custom_errors', 'You don`t have the right permission');
      }
        $validated = $request->validate([
            'tonnage' => [
                'required',
                Rule::unique('machine_tonnages', 'tonnage')->whereNull('deleted_at')
            ],
          ]);

      $machine_tonnage= new MachineTonnage();
      $machine_tonnage->tonnage = $request->tonnage;
      $machine_tonnage->save();
      return redirect()->route('machine_tonage.index')->with('custom_success', 'Machine Tonnage Created Successfully.');
    }

    public function edit($id)
    {
      if (!Auth::user()->hasPermissionTo('Machine Tonnage Edit')) {
        return back()->with('custom_errors', 'You don`t have the right permission');
    }
      $machine_tonnage = MachineTonnage::find($id);
      return view('database.machine_tonnage.edit', compact('machine_tonnage'));
    }

    public function update(Request $request, $id)
    {
      if (!Auth::user()->hasPermissionTo('Machine Tonnage Edit')) {
        return back()->with('custom_errors', 'You don`t have the right permission');
    }
        $validated = $request->validate([
          'tonnage' => [
            'required',
            Rule::unique('machine_tonnages', 'tonnage')->whereNull('deleted_at')
        ],
          ]);
      $machine_tonnage = MachineTonnage::find($id);
      $machine_tonnage->tonnage= $request->tonnage;
      $machine_tonnage->save();
      return redirect()->route('machine_tonage.index')->with('custom_success', 'Machine Tonnage Updated Successfully.');
    }

    public function view($id)
    {
      if (!Auth::user()->hasPermissionTo('Machine Tonnage View')) {
        return back()->with('custom_errors', 'You don`t have the right permission');
    }
      $machine_tonnage = MachineTonnage::find($id);
      return view('database.machine_tonnage.view', compact('machine_tonnage'));
    }

    public function destroy($id)
    {
      if (!Auth::user()->hasPermissionTo('Machine Tonnage Delete')) {
        return back()->with('custom_errors', 'You don`t have the right permission');
    }
      $machine_tonnages = MachineTonnage::find($id);
      $machine_tonnages->delete();
      return redirect()->route('machine_tonage.index')->with('custom_success', 'Machine Tonnage Deleted Successfully.');
    }
}
