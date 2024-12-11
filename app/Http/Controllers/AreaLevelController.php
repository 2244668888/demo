<?php

namespace App\Http\Controllers;
use App\Models\AreaLevel;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Helper;
use App\Models\AreaRack;
use Yajra\DataTables\Facades\DataTables;

class AreaLevelController extends Controller
{
    public function Data(Request $request)
    {

        if ($request->ajax()) {

            $query = AreaLevel::select(
                'area_levels.id',
                'area_levels.name',
                'area_levels.code',
            );

// dd($request->all());

            $datatable = DataTables::eloquent($query)
                ->addIndexColumn()

                ->addColumn('action', function($row){
                    $btn = '<div class="d-flex"><a class="btn btn-info btn-sm mx-2" href="' .
                    route('area_level.edit', $row->id) .
                    '"><i class="bi bi-pencil"></i></a>
                    <a class="btn btn-success btn-sm mx-2" href="' .
                    route('area_level.view', $row->id) .
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
                                                <form method="POST" action="' . route('area_level.destroy', $row->id) . '">
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
        Auth::user()->hasPermissionTo('Area Level List') ||
        Auth::user()->hasPermissionTo('Area Level Create') ||
        Auth::user()->hasPermissionTo('Area Level Edit') ||
        Auth::user()->hasPermissionTo('Area Level View') ||
        Auth::user()->hasPermissionTo('Area Level Delete')
    ) {

      $area_levels = AreaLevel::all();

      return view('database.area_level.index', compact('area_levels'));
    }
    return back()->with('custom_errors', 'You don`t have the right permission');
  }
    public function create()
    {
      if (!Auth::user()->hasPermissionTo('Area Level Create')) {
        return back()->with('custom_errors', 'You don`t have the right permission');
    }
      return view('database.area_level.create');
    }

    public function store(Request $request)
    {
      if (!Auth::user()->hasPermissionTo('Area Level Create')) {
        return back()->with('custom_errors', 'You don`t have the right permission');
    }
        $validated = $request->validate([
            'name' => [
                'required',
                Rule::unique('area_levels', 'name')->whereNull('deleted_at')
            ],
            'code' => [
                'required',
                Rule::unique('area_levels', 'code')->whereNull('deleted_at')
            ],
          ]);

      $arealevel= new AreaLevel();
      $arealevel->name = $request->name;
      $arealevel->code = $request->code;
      $arealevel->save();
      return redirect()->route('area_level.index')->with('custom_success', 'Area Level Created Successfully.');
    }

    public function edit($id)
    {
      if (!Auth::user()->hasPermissionTo('Area Level Edit')) {
        return back()->with('custom_errors', 'You don`t have the right permission');
    }
      $area_level = AreaLevel::find($id);
      return view('database.area_level.edit', compact('area_level'));
    }

    public function update(Request $request, $id)
    {
      if (!Auth::user()->hasPermissionTo('Area Level Edit')) {
        return back()->with('custom_errors', 'You don`t have the right permission');
    }

        $validated = $request->validate([
            'name' => [
                'required',
                Rule::unique('area_levels', 'name')->whereNull('deleted_at')->ignore($id)
            ],
            'code' => [
                'required',
                Rule::unique('area_levels', 'code')->whereNull('deleted_at')->ignore($id)
            ],
          ]);
      $arealevel = AreaLevel::find($id);
      $arealevel->name = $request->name;
      $arealevel->code = $request->code;
      $arealevel->save();
      return redirect()->route('area_level.index')->with('custom_success', 'Area Level Updated Successfully.');
    }

    public function view($id)
    {
      if (!Auth::user()->hasPermissionTo('Area Level View')) {
        return back()->with('custom_errors', 'You don`t have the right permission');
    }
      $area_level = AreaLevel::find($id);
      return view('database.area_level.view', compact('area_level'));
    }

    public function destroy($id)
    {
      if (!Auth::user()->hasPermissionTo('Area Level Delete')) {
        return back()->with('custom_errors', 'You don`t have the right permission');
    }
      $arealevel = AreaLevel::find($id);
      $level = AreaRack::whereJsonContains('level_id', $arealevel->id)->first();
        if($level){
            return back()->with('custom_errors', 'This LEVEL is used in RACK!');
        }
      $arealevel->delete();
      return redirect()->route('area_level.index')->with('custom_success', 'Area Level Deleted Successfully.');
    }
}
