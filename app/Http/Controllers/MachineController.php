<?php

namespace App\Http\Controllers;
use App\Models\Machine;
use App\Models\MachineTonnage;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Helper;
use Yajra\DataTables\Facades\DataTables;

class MachineController extends Controller
{
    public function Data(Request $request)
    {



        if ($request->ajax()) {

            $query = Machine::select(
                'machines.id',
                'machines.name',
                'machines.code',
                'machines.tonnage_id',
                'machines.category',
            )
            ->with('tonnage');

            $datatable = DataTables::eloquent($query)
                ->addIndexColumn()
                ->addColumn('tonnage_id', function($row){
                    return $row->tonnage->tonnage ?? '-';
                })
                ->addColumn('action', function($row){
                    $btn = '<div class="d-flex"><a class="btn btn-info btn-sm mx-2" href="' .
                    route('machine.edit', $row->id) .
                    '"><i class="bi bi-pencil"></i></a>
                    <a class="btn btn-success btn-sm mx-2" href="' .
                    route('machine.view', $row->id) .
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
                                                <form method="POST" action="' . route('machine.destroy', $row->id) . '">
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
                ->rawColumns(['action', 'tonnage_id'])

                ->filterColumn('tonnage_id', function($query, $keyword) {
                    $query->whereHas('tonnage', function($q) use ($keyword) {
                        $q->where('tonnage', 'like', "%{$keyword}%");
                    });
                });

                if($request->search['value'] == null ){

                    $datatable = $datatable->filter(function ($query) use ($request) {
                    if ($request->has('name') && !is_null($request->name)) {
                        $query->where('name', 'like', "%{$request->name}%");
                    }
                    if ($request->has('code') && !is_null($request->code)) {
                        $query->where('code', 'like', "%{$request->code}%");
                    }
                    if ($request->has('tonnage') && !is_null($request->tonnage)) {
                        $query->whereHas('tonnage', function($q) use ($request) {
                            $q->where('tonnage', 'like', "%{$request->tonnage}%");
                        });
                    }
                    if ($request->has('category') && !is_null($request->category)) {
                        $query->where('category', 'like', "%{$request->category}%");
                    }

                });
            }
            return $datatable->make(true);
        }

    }
    public function index()
    {
      if (
        Auth::user()->hasPermissionTo('Machine List') ||
        Auth::user()->hasPermissionTo('Machine Create') ||
        Auth::user()->hasPermissionTo('Machine Edit') ||
        Auth::user()->hasPermissionTo('Machine View') ||
        Auth::user()->hasPermissionTo('Machine Delete')
    ) {

      $machines = Machine::all();
      return view('database.machine.index', compact('machines'));
    }
    return back()->with('custom_errors', 'You don`t have the right permission');
  }
    public function create()
    {
      if (!Auth::user()->hasPermissionTo('Machine Create')) {
        return back()->with('custom_errors', 'You don`t have the right permission');
    }

      $tonnages = MachineTonnage::select('id','tonnage')->get();
      return view('database.machine.create', compact('tonnages'));
    }

    public function store(Request $request)
    {
      if (!Auth::user()->hasPermissionTo('Machine Create')) {
        return back()->with('custom_errors', 'You don`t have the right permission');
    }
        $validated = $request->validate([
            'name' => [
                'required',
                Rule::unique('machines', 'name')->whereNull('deleted_at')
            ],
            'code' => [
                'required',
                Rule::unique('machines', 'code')->whereNull('deleted_at')
            ],
            'tonnage' => [
                'required'
            ],
            'category' => [
                'required'
            ],
          ]);

      $machine= new Machine();
      $machine->name = $request->name;
      $machine->code = $request->code;
      $machine->tonnage_id = $request->tonnage;
      $machine->category = $request->category;
      $machine->save();
      return redirect()->route('machine.index')->with('custom_success', 'Machine Created Successfully.');
    }

    public function edit($id)
    {
      if (!Auth::user()->hasPermissionTo('Machine Edit')) {
        return back()->with('custom_errors', 'You don`t have the right permission');
    }
      $machine = Machine::find($id);
      $tonnages = MachineTonnage::select('id','tonnage')->get();
      return view('database.machine.edit', compact('machine', 'tonnages'));
    }

    public function update(Request $request, $id)
    {
      if (!Auth::user()->hasPermissionTo('Machine Edit')) {
        return back()->with('custom_errors', 'You don`t have the right permission');
    }
    $validated = $request->validate([
        'name' => [
            'required',
            Rule::unique('machines', 'name')->whereNull('deleted_at')->ignore($id)
        ],
        'code' => [
            'required',
            Rule::unique('machines', 'code')->whereNull('deleted_at')->ignore($id)
        ],
        'tonnage' => [
            'required'
        ],
        'category' => [
            'required'
        ],
      ]);

      $machine = Machine::find($id);
      $machine->name = $request->name;
      $machine->code = $request->code;
      $machine->tonnage_id = $request->tonnage;
      $machine->category = $request->category;
      $machine->save();
      return redirect()->route('machine.index')->with('custom_success', 'Machine Updated Successfully.');
    }

    public function view($id)
    {
      if (!Auth::user()->hasPermissionTo('Machine View')) {
        return back()->with('custom_errors', 'You don`t have the right permission');
    }
      $machine = Machine::find($id);
      $tonnages = MachineTonnage::select('id','tonnage')->get();
      return view('database.machine.view', compact('machine', 'tonnages'));
    }

    public function destroy($id)
    {
      if (!Auth::user()->hasPermissionTo('Machine Delete')) {
        return back()->with('custom_errors', 'You don`t have the right permission');
    }
      $machines = Machine::find($id);
      $machines->delete();
      return redirect()->route('machine.index')->with('custom_success', 'Machine Deleted Successfully.');
    }
}
