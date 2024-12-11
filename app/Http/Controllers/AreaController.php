<?php

namespace App\Http\Controllers;
use App\Models\Area;
use App\Models\AreaRack;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use App\Models\AreaLocation;
use App\Models\Location;
use Yajra\DataTables\Facades\DataTables;

class AreaController extends Controller
{

    public function Data(Request $request)
    {

        if ($request->ajax()) {

            $query = Area::select(
                'areas.id',
                'areas.name',
                'areas.code',
                'areas.department',

            );

// dd($request->all());

            $datatable = DataTables::eloquent($query)
                ->addIndexColumn()

                ->addColumn('action', function($row){
                    $btn = '<div class="d-flex"><a class="btn btn-info btn-sm mx-2" href="' .
                    route('area.edit', $row->id) .
                    '"><i class="bi bi-pencil"></i></a>
                    <a class="btn btn-success btn-sm mx-2" href="' .
                    route('area.view', $row->id) .
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
                                                <form method="POST" action="' . route('area.destroy', $row->id) . '">
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
                    if ($request->has('department') && !is_null($request->department)) {
                        $query->where('department', 'like', "%{$request->department}%");
                    }

                });
            }

               return $datatable->make(true);
        }




    }
    public function index()
    {
      if (
        Auth::user()->hasPermissionTo('Area List') ||
        Auth::user()->hasPermissionTo('Area Create') ||
        Auth::user()->hasPermissionTo('Area Edit') ||
        Auth::user()->hasPermissionTo('Area View') ||
        Auth::user()->hasPermissionTo('Area Delete')
    ) {

      $areas = Area::all();
      return view('database.area.index', compact('areas'));
    }
    return back()->with('custom_errors', 'You don`t have the right permission');
  }
    public function create()
    {
      if (!Auth::user()->hasPermissionTo('Area Create')) {
        return back()->with('custom_errors', 'You don`t have the right permission');
    }
      $area_racks = AreaRack::select('id','name')->get();
      return view('database.area.create', compact('area_racks'));
    }

    public function store(Request $request)
    {
      if (!Auth::user()->hasPermissionTo('Area Create')) {
        return back()->with('custom_errors', 'You don`t have the right permission');
    }

        $validated = $request->validate([
            'name' => [
                'required',
                Rule::unique('areas', 'name')->whereNull('deleted_at')
            ],
            'code' => [
                'required',
                Rule::unique('areas', 'code')->whereNull('deleted_at')
            ],
            'department' => [
                'required'
            ],
            'area_rack' => [
                'required'
            ],
          ]);

      $area= new Area();
      $area->name = $request->name;
      $area->code = $request->code;
      $area->rack_id =json_encode ($request->area_rack);
      $area->department = $request->department;
      $area->save();

        $racks = AreaRack::whereIn('id', $request->area_rack)->get();
        foreach($racks as $rack){
            $levels = json_decode($rack->level_id);
            foreach($levels as $level){
                AreaLocation::where('area_id', '=', $area->id)->where('rack_id', '=', $rack->id)->where('level_id', '=', $level)->where('department', '=', $area->department)->delete();
                $location = new AreaLocation();
                $location->area_id = $area->id;
                $location->rack_id = $rack->id;
                $location->level_id = $level;
                $location->department = $area->department;
                $location->save();
            }
        }

      return redirect()->route('area.index')->with('custom_success', 'Area Rack Created Successfully.');
    }

    public function edit($id)
    {
      if (!Auth::user()->hasPermissionTo('Area Edit')) {
        return back()->with('custom_errors', 'You don`t have the right permission');
    }
      $area = Area::find($id);
      $area_racks = AreaRack::select('id','name')->get();
      return view('database.area.edit', compact('area','area_racks'));
    }

    public function update(Request $request, $id)
    {
      if (!Auth::user()->hasPermissionTo('Area Edit')) {
        return back()->with('custom_errors', 'You don`t have the right permission');
    }
        $validated = $request->validate([
            'name' => [
                'required',
                Rule::unique('areas', 'name')->whereNull('deleted_at')->ignore($id)
            ],
            'code' => [
                'required',
                Rule::unique('areas', 'code')->whereNull('deleted_at')->ignore($id)
            ],
            'department' => [
                'required'
            ],
            'area_rack' => [
                'required'
            ],
        ]);

        $area = Area::find($id);
        $existingShelves = json_decode($area->rack_id);

        $shelvesToRemove = array_diff($existingShelves, $request->area_rack);

        $locationsWithUsedQty = Location::where('area_id', $area->id)->whereIn('rack_id', $shelvesToRemove)->where('department', '!=', $area->department)->where('used_qty', '!=', 0)->where('used_qty', '!=', null)->exists();

        if ($locationsWithUsedQty) {
            return redirect()->back()->with('custom_errors', 'You try to remove RACK or RACKS which contain`s some quantity !');
        }

      $area = Area::find($id);
      $area->name = $request->name;
      $area->code = $request->code;
      $area->rack_id = json_encode ($request->area_rack);
      $area->department = $request->department;
      $area->save();

        $racks = AreaRack::whereIn('id', $request->area_rack)->get();
        foreach($racks as $rack){
            $levels = json_decode($rack->level_id);
            foreach($levels as $level){
                AreaLocation::where('area_id', '=', $area->id)->where('rack_id', '=', $rack->id)->where('level_id', '=', $level)->where('department', '=', $area->department)->delete();
                $location = new AreaLocation();
                $location->area_id = $area->id;
                $location->rack_id = $rack->id;
                $location->level_id = $level;
                $location->department = $area->department;
                $location->save();
            }
        }

      return redirect()->route('area.index')->with('custom_success', 'Area Rack Updated Successfully.');
    }

    public function view($id)
    {
      if (!Auth::user()->hasPermissionTo('Area View')) {
        return back()->with('custom_errors', 'You don`t have the right permission');
    }
      $area = Area::find($id);
      $area_racks = AreaRack::select('id','name')->get();
      return view('database.area.view', compact('area','area_racks'));
    }

    public function destroy($id)
    {
      if (!Auth::user()->hasPermissionTo('Area Delete')) {
        return back()->with('custom_errors', 'You don`t have the right permission');
    }

      $area = Area::find($id);
      $location = Location::where('area_id', '=', $id)->where('department', '=', $area->department)->where('used_qty', '!=', 0)->orWhere('used_qty', '!=', null)->first();
        if ($location) {
            return redirect()->back()->with('custom_errors', 'You try to delete AREA which contain`s some quantity !');
        }
        $racks = AreaRack::whereIn('id', json_decode($area->rack_id))->get();
        foreach($racks as $rack){
            $levels = json_decode($rack->level_id);
            foreach($levels as $level){
                AreaLocation::where('area_id', '=', $area->id)->where('department', '=', $area->department)->where('rack_id', '=', $rack->id)->where('level_id', '=', $level)->delete();
            }
        }
      $area->delete();
      return redirect()->route('area.index')->with('custom_success', 'Area Rack Deleted Successfully.');
    }
}
