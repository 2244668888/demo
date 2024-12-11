<?php

namespace App\Http\Controllers;
use App\Models\Process;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Helper;
use Yajra\DataTables\Facades\DataTables;

class ProcessController extends Controller
{
    //




    public function Data(Request $request)
    {
        if ($request->ajax()) {

            $query = Process::select(
                'id',
                'name',
                'code',
                'description',
            );



    // dd($request->all());

            $datatable = DataTables::eloquent($query)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '<div class="d-flex"><a class="btn btn-info btn-sm mx-2" href="' .
                    route('process.edit', $row->id) .
                    '"><i class="bi bi-pencil"></i></a>
                    <a class="btn btn-success btn-sm mx-2" href="' .
                    route('process.view', $row->id) .
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
                                                <form method="POST" action="' . route('process.destroy', $row->id) . '">
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
                    if ($request->has('name') && !is_null($request->name)) {
                        $query->where('name', 'like', "%{$request->name}%");
                    }
                    if ($request->has('code') && !is_null($request->code)) {
                        $query->where('code', 'like', "%{$request->code}%");
                    }
                    if ($request->has('description') && !is_null($request->description)) {
                        $query->where('description', 'like', "%{$request->description}%");
                    }

                });
            }
            return $datatable->make(true);
        }

    }
    public function index()
    {
      if (
        Auth::user()->hasPermissionTo('Process List') ||
        Auth::user()->hasPermissionTo('Process Create') ||
        Auth::user()->hasPermissionTo('Process Edit') ||
        Auth::user()->hasPermissionTo('Process View') ||
        Auth::user()->hasPermissionTo('Process Delete')
    ) {
      $processs = Process::all();
      return view('database.process.index', compact('processs'));
    }
    return back()->with('custom_errors', 'You don`t have the right permission');
  }
    public function create()
    {
      if (!Auth::user()->hasPermissionTo('Process Create')) {
        return back()->with('custom_errors', 'You don`t have the right permission');
      }
      return view('database.process.create');
    }

    public function store(Request $request)
    {
      if (!Auth::user()->hasPermissionTo('Process Create')) {
        return back()->with('custom_errors', 'You don`t have the right permission');
    }
        $validated = $request->validate([
            'name' => [
                'required',
                Rule::unique('processes', 'name')->whereNull('deleted_at')
            ],
            'code' => [
                'required',
                Rule::unique('processes', 'code')->whereNull('deleted_at')
            ],
          ]);

      $process= new Process();
      $process->name = $request->name;
      $process->code = $request->code;
      $process->description = $request->description;
      $process->save();
      return redirect()->route('process.index')->with('custom_success', 'Process Created Successfully.');
    }

    public function edit($id)
    {
      if (!Auth::user()->hasPermissionTo('Process Edit')) {
        return back()->with('custom_errors', 'You don`t have the right permission');
    }
      $process = Process::find($id);
      return view('database.process.edit', compact('process'));
    }

    public function update(Request $request, $id)
    {
      if (!Auth::user()->hasPermissionTo('Process Edit')) {
        return back()->with('custom_errors', 'You don`t have the right permission');
    }
        $validated = $request->validate([
            'name' => [
                'required',
                Rule::unique('processes', 'name')->whereNull('deleted_at')->ignore($id)
            ],
            'code' => [
                'required',
                Rule::unique('processes', 'code')->whereNull('deleted_at')->ignore($id)
            ],
          ]);
      $process = Process::find($id);
      $process->name = $request->name;
      $process->code = $request->code;
      $process->description = $request->description;
      $process->save();
      return redirect()->route('process.index')->with('custom_success', 'Process Updated Successfully.');
    }

    public function view($id)
    {
      if (!Auth::user()->hasPermissionTo('Process View')) {
        return back()->with('custom_errors', 'You don`t have the right permission');
    }
      $process = Process::find($id);
      return view('database.process.view', compact('process'));
    }

    public function destroy($id)
    {
      if (!Auth::user()->hasPermissionTo('Process Delete')) {
        return back()->with('custom_errors', 'You don`t have the right permission');
    }
      $process = Process::find($id);
      $process->delete();
      return redirect()->route('process.index')->with('custom_success', 'Process Deleted Successfully.');
    }
}
