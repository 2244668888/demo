<?php

namespace App\Http\Controllers;
use App\Models\AreaRack;
use App\Models\AreaLevel;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Helper;
use App\Models\Area;
use App\Models\Location;
use Yajra\DataTables\Facades\DataTables;

class AreaRackController extends Controller
{
    public function Data(Request $request)
    {

        if ($request->ajax()) {

            $query = AreaRack::select(
                'area_racks.id',
                'area_racks.name',
                'area_racks.code',
            );

            $datatable = DataTables::eloquent($query)
                ->addIndexColumn()

                ->addColumn('action', function($row){
                    $btn = '<div class="d-flex"><a class="btn btn-info btn-sm mx-2" href="' .
                    route('area_rack.edit', $row->id) .
                    '"><i class="bi bi-pencil"></i></a>
                    <a class="btn btn-success btn-sm mx-2" href="' .
                    route('area_rack.view', $row->id) .
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
                                                <form method="POST" action="' . route('area_rack.destroy', $row->id) . '">
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
        Auth::user()->hasPermissionTo('Area Rack List') ||
        Auth::user()->hasPermissionTo('Area Rack Create') ||
        Auth::user()->hasPermissionTo('Area Rack Edit') ||
        Auth::user()->hasPermissionTo('Area Rack View') ||
        Auth::user()->hasPermissionTo('Area Rack Delete')
    ) {
      $area_racks = AreaRack::all();
      return view('database.area_rack.index', compact('area_racks'));
    }
    return back()->with('custom_errors', 'You don`t have the right permission');
  }
    public function create()
    {
      if (!Auth::user()->hasPermissionTo('Area Rack Create')) {
        return back()->with('custom_errors', 'You don`t have the right permission');
    }
      $area_levels = AreaLevel::all();
      return view('database.area_rack.create', compact('area_levels'));
    }

    public function store(Request $request)
    {
      if (!Auth::user()->hasPermissionTo('Area Rack Create')) {
        return back()->with('custom_errors', 'You don`t have the right permission');
    }
        $validated = $request->validate([
            'name' => [
                'required',
                Rule::unique('area_racks', 'name')->whereNull('deleted_at')
            ],
            'code' => [
                'required',
                Rule::unique('area_racks', 'code')->whereNull('deleted_at')
            ],
            'area_level' => [
                'required'
            ],
          ]);

      $arearack= new AreaRack();
      $arearack->name = $request->name;
      $arearack->code = $request->code;
      $arearack->level_id = json_encode ($request->area_level);
      $arearack->save();
      return redirect()->route('area_rack.index')->with('custom_success', 'Area Rack Created Successfully.');
    }

    public function edit($id)
    {
      if (!Auth::user()->hasPermissionTo('Area Rack Edit')) {
        return back()->with('custom_errors', 'You don`t have the right permission');
    }
      $area_rack = AreaRack::find($id);
      $area_levels = AreaLevel::all();
      return view('database.area_rack.edit', compact('area_rack','area_levels'));
    }

    public function update(Request $request, $id)
    {
      if (!Auth::user()->hasPermissionTo('Area Rack Edit')) {
        return back()->with('custom_errors', 'You don`t have the right permission');
    }
        $validated = $request->validate([
            'name' => [
                'required',
                Rule::unique('area_racks', 'name')->whereNull('deleted_at')->ignore($id)
            ],
            'code' => [
                'required',
                Rule::unique('area_racks', 'code')->whereNull('deleted_at')->ignore($id)
            ],
            'area_level' => [
                'required'
            ]
          ]);

        $area_Rack =  AreaRack::find($id);
        $existingLevels = json_decode($area_Rack->level_id);

        $levelsToRemove = array_diff($existingLevels, $request->area_level);

        $locationsWithUsedQty = Location::where('rack_id', $area_Rack->id)->whereIn('level_id', $levelsToRemove)->where('used_qty', '!=', 0)->where('used_qty', '!=', null)->exists();

        if ($locationsWithUsedQty) {
            return redirect()->back()->with('custom_errors', 'You try to remove RACK or RACKS which contain`s some quantity !');
        }

      $arearack = AreaRack::find($id);
      $arearack->name = $request->name;
      $arearack->code = $request->code;
      $arearack->level_id =json_encode ($request->area_level);
      $arearack->save();
      return redirect()->route('area_rack.index')->with('custom_success', 'Area Rack Updated Successfully.');
    }

    public function view($id)
    {
      if (!Auth::user()->hasPermissionTo('Area Rack View')) {
        return back()->with('custom_errors', 'You don`t have the right permission');
    }
      $area_rack = AreaRack::find($id);
      $area_levels = AreaLevel::all();
      return view('database.area_rack.view', compact('area_rack','area_levels'));
    }

    public function destroy($id)
    {
      if (!Auth::user()->hasPermissionTo('Area Rack Delete')) {
        return back()->with('custom_errors', 'You don`t have the right permission');
    }
      $arearack = AreaRack::find($id);
        $rack = Area::whereJsonContains('rack_id', $arearack->id)->first();
        if($rack){
            return back()->with('custom_errors', 'This RACK is used in AREA!');
        }
      $arearack->delete();
      return redirect()->route('area_rack.index')->with('custom_success', 'Area Rack Deleted Successfully.');
    }
}
