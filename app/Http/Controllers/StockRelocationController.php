<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\InitailNo;
use App\Models\AreaLocation;
use App\Models\Location;
use App\Models\StockRelocation;
use App\Models\StockRelocationDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StockRelocationController extends Controller
{
    public function Data(Request $request)
    {
        if ($request->ajax() && $request->input('columnsData') != null) {
            $columnsData = $request->input('columnsData');
            $draw = $request->input('draw');
            $start = $request->input('start');
            $length = $request->input('length');
            $search = $request->input('search.value');
            $orderByColumnIndex = $request->input('order.0.column'); // Get the index of the column to sort by
            $orderByDirection = $request->input('order.0.dir'); // Get the sort direction ('asc' or 'desc')

            $query = StockRelocation::select('id', 'date', 'ref_no','description','created_by')->with('user');


            // Apply search if a search term is provided
            if (!empty($search)) {
                $searchLower = strtolower($search);
                $query->where(function ($q) use ($searchLower) {
                    $q

                        ->where('date', 'like', '%' . $searchLower . '%')

                        ->orWhere('ref_no', 'like', '%' . $searchLower . '%')
                        ->orWhere('description', 'like', '%' . $searchLower . '%')

                        ->orWhereHas('user', function ($query) use ($searchLower) {
                            $query->where('user_name', 'like', '%' . $searchLower . '%');
                        });
                });
            }
            $results = null;

            if (!empty($columnsData)) {

                $sortableColumns = [
                    1 => 'date',
                    2 => 'ref_no',
                    3 => 'description',
                    4 => 'created_by',


                    // Add more columns as needed
                ];
                if($orderByColumnIndex != null){
                    if($orderByColumnIndex == "0"){
                        $orderByColumn = 'created_at';
                        $orderByDirection = 'ASC';
                    }else{
                        $orderByColumn = $sortableColumns[$orderByColumnIndex];
                    }
                }else{
                    $orderByColumn = 'created_at';
                }
                if($orderByDirection == null){
                    $orderByDirection = 'ASC';
                }
                $results = $query->where(function ($q) use ($columnsData) {
                    foreach ($columnsData as $column) {
                        $searchLower = strtolower($column['value']);

                        switch ($column['index']) {
                            case 1:
                                $q->where('date', 'like', '%' . $searchLower . '%');

                                break;
                            case 2:
                                $q->where('ref_no', 'like', '%' . $searchLower . '%');


                                break;
                                case 3:
                                    $q->where('description', 'like', '%' . $searchLower . '%');


                                    break;
                                    case 4:

                                        $q->whereHas('user', function ($query) use ($searchLower) {
                                            $query->where('user_name', 'like', '%' . $searchLower . '%');
                                        });
                                        break;



                            default:
                                break;
                        }
                    }
                })->orderBy($orderByColumn, $orderByDirection)->get();

            }

            // type_of_rejection and format the results for DataTables
            $recordsTotal = $results ? $results->count() : 0;

            // Check if there are results before applying skip and take
            if ($results->isNotEmpty()) {
                $uom = $results->skip($start)->take($length)->all();
            } else {
                $uom = [];
            }

            $index = 0;
            foreach ($uom as $row) {
                $row->sr_no = $start + $index + 1;

                // $status = '';

                // $row->date = Carbon::parse($row->date)->format('d/m/Y');
                // dd($row->status);


                $row->action = '<a class="btn btn-success btn-sm"
                                        href="' . route('stock_relocation.view', $row->id)  .'"><i
                                            class="bi bi-eye"></i></a>
                                    <a class="btn btn-info btn-sm"
                                        href="' . route('stock_relocation.edit', $row->id)  .'"><i
                                            class="bi bi-pencil"></i></a>
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
                                                <form method="POST" action="' . route('stock_relocation.destroy', $row->id) . '">
                                                    ' . csrf_field() . '
                                                    ' . method_field('DELETE') . '
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>';
                // $row->status = $status;

                $index++;
            }

            // // Continue with your response
            $uomsWithoutAction = array_map(function ($row) {
                return $row;
            }, $uom);

            return response()->json([
                'draw' => $draw,
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsTotal, // Total records after filtering
                'data' => array_values($uomsWithoutAction),
            ]);
        } elseif ($request->ajax()) {

            $draw = $request->input('draw');
            $start = $request->input('start');
            $length = $request->input('length');
            $search = $request->input('search.value');
            $orderByColumnIndex = $request->input('order.0.column'); // Get the index of the column to sort by
            $orderByDirection = $request->input('order.0.dir'); // Get the sort direction ('asc' or 'desc')

            $query = StockRelocation::select('id', 'date', 'ref_no','description','created_by')->with('user');


            // Apply search if a search term is provided
            if (!empty($search)) {
                $searchLower = strtolower($search);
                $query->where(function ($q) use ($searchLower) {
                    $q

                        ->where('date', 'like', '%' . $searchLower . '%')

                        ->orWhere('ref_no', 'like', '%' . $searchLower . '%')
                        ->orWhere('description', 'like', '%' . $searchLower . '%')

                        ->orWhereHas('user', function ($query) use ($searchLower) {
                            $query->where('user_name', 'like', '%' . $searchLower . '%');
                        });
                });
            }


            $sortableColumns = [
                1 => 'date',
                2 => 'ref_no',
                3 => 'description',
                4 => 'created_by',


                // Add more columns as needed
            ];
            if($orderByColumnIndex != null){
                if($orderByColumnIndex != "0"){
                    $orderByColumn = $sortableColumns[$orderByColumnIndex];
                    $query->orderBy($orderByColumn, $orderByDirection);
                }else{
                    $query->latest('created_at');
                }
            }else{
                $query->latest('created_at');
            }
            $recordsTotal = $query->count();

            $uom = $query
                ->skip($start)
                ->take($length)
                ->get();

            $uom->each(function ($row, $index)  use (&$start) {
                $row->sr_no = $start + $index + 1;

                $row->action = '<a class="btn btn-success btn-sm"
                href="' . route('stock_relocation.view', $row->id)  .'"><i
                    class="bi bi-eye"></i></a>
            <a class="btn btn-info btn-sm"
                href="' . route('stock_relocation.edit', $row->id)  .'"><i
                    class="bi bi-pencil"></i></a>
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
                                                <form method="POST" action="' . route('stock_relocation.destroy', $row->id) . '">
                                                    ' . csrf_field() . '
                                                    ' . method_field('DELETE') . '
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>';


            });

            return response()->json([
                'draw' => $draw,
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsTotal, // Total records after filtering
                'data' => $uom,
            ]);
        }
    }
    public function index(){
        if (
            Auth::user()->hasPermissionTo('Stock Relocation List') ||
            Auth::user()->hasPermissionTo('Stock Relocation Create') ||
            Auth::user()->hasPermissionTo('Stock Relocation Edit') ||
            Auth::user()->hasPermissionTo('Stock Relocation View') ||
            Auth::user()->hasPermissionTo('Stock Relocation Delete')
        ) {
            $stock_relocations = StockRelocation::all();
            return view('wms.operations.stock-reloction.index', compact('stock_relocations'));
        }
        return back()->with('custom_errors', 'You don`t have Right Permission');
    }

    public function create(){
        if (!Auth::user()->hasPermissionTo('Stock Relocation Create')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }
        $ref_no = '';
        $year = Carbon::now('Asia/Kuala_Lumpur')->format('y');
        $setting = InitailNo::where('screen', 'Stock Relocation')->first();

        if ($setting) {
            $stock = StockRelocation::orderBy('id', 'DESC')->first();
            if ($stock) {
                // Extract running_no from $stock->ref_no which is in format 'SR/running_no/year'
                $parts = explode('/', $stock->ref_no);
                if (count($parts) == 3) {
                    $running_no = (int) $parts[1] + 1;
                } else {
                    $running_no = 1; // Fallback in case the format is unexpected
                }
                $ref_no = $setting->ref_no . '/' . $running_no . '/' . $year;
            } else {
                $ref_no = $setting->ref_no . '/' . $setting->running_no . '/' . $year;
            }
        } else {
            $stock = StockRelocation::orderBy('id', 'DESC')->first();
            if ($stock) {
                // Extract running_no from $stock->ref_no which is in format 'SR/running_no/year'
                $parts = explode('/', $stock->ref_no);
                if (count($parts) == 3) {
                    $running_no = (int) $parts[1] + 1;
                } else {
                    $running_no = 1; // Fallback in case the format is unexpected
                }
                $ref_no = 'SR/' . $running_no . '/' . $year;
            } else {
                $ref_no = 'SR/1/' . $year;
            }
        }

        $locations = AreaLocation::select('area_id', 'rack_id', 'level_id')->with('area', 'rack', 'level')->get();
        return view('wms.operations.stock-reloction.create', compact('ref_no', 'locations'));
    }

    public function products(Request $request){
        $products = Location::where('area_id', '=', $request->area_id)->where('rack_id', '=', $request->rack_id)->where('level_id', '=', $request->level_id)->with('product.units')->get()
        ->groupBy('product_id')
        ->map(function($group) {
            return [
                'product_id' => $group->first()->product_id,
                'part_no' => $group->first()->product->part_no,
                'part_name' => $group->first()->product->part_name,
                'units' => $group->first()->product->units,
                'used_qty' => $group->sum('used_qty')
            ];
        });
        return $products;
    }

    public function store(Request $request)
    {
        if (!Auth::user()->hasPermissionTo('Stock Relocation Create')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }

        $validated = $request->validate([
            'date' => 'required',
            'ref_no' => 'required',
            'previous_location' => 'required',
            'new_location' => 'required',
            'products' => 'required'
        ]);

        $stock_relocation = new StockRelocation();
        $stock_relocation->date = $request->date;
        $stock_relocation->ref_no = $request->ref_no;
        $stock_relocation->description = $request->description;

        $stock_relocation->previous_area_id = $request->previous_area_id;
        $stock_relocation->previous_rack_id = $request->previous_rack_id;
        $stock_relocation->previous_level_id = $request->previous_level_id;
        $stock_relocation->new_area_id = $request->new_area_id;
        $stock_relocation->new_rack_id = $request->new_rack_id;
        $stock_relocation->new_level_id = $request->new_level_id;

        $stock_relocation->created_by = Auth::user()->id;
        $stock_relocation->save();

        foreach($request->products as $value){
            $stock_relocation_product = new StockRelocationDetail();
            $stock_relocation_product->stock_relocation_id = $stock_relocation->id;
            $stock_relocation_product->product_id = $value['product_id'] ?? null;
            $stock_relocation_product->available_qty = $value['available_qty'] ?? 0;
            $stock_relocation_product->qty = $value['qty'] ?? 0;
            $stock_relocation_product->save();

            $location = Location::where('area_id', $stock_relocation->previous_area_id)->where('rack_id', $stock_relocation->previous_rack_id)->where('level_id', $stock_relocation->previous_level_id)->where('product_id', $stock_relocation_product->product_id)->first();
            if ($location) {
                $location->used_qty -= $stock_relocation_product->qty ?? 0;
                $location->save();
            }
            
            $location1 = Location::where('area_id', $stock_relocation->new_area_id)->where('rack_id', $stock_relocation->new_rack_id)->where('level_id', $stock_relocation->new_level_id)->where('product_id', $stock_relocation_product->product_id)->first();
            // dd($location1); 
            if ($location1) {
                $location1->used_qty += $stock_relocation_product->qty ?? 0;
                if($location1->lot_no != null || $location1->lot_no != ''){
                    $location1->lot_no = $location->lot_no;
                }
            }else{
                $location1 = new Location();
                $location1->area_id = $stock_relocation->new_area_id;
                $location1->rack_id = $stock_relocation->new_rack_id;
                $location1->level_id = $stock_relocation->new_level_id;
                $location1->product_id = $stock_relocation_product->product_id;
                $location1->used_qty = $stock_relocation_product->qty ?? 0;
                $location1->lot_no = $location->lot_no;
            }
            $location1->save();
        }


        NotificationController::Notification(
            'Stock Relocation',
            'Create',
            route('stock_relocation.view', $stock_relocation->id)
        );

        return redirect()->route('stock_relocation.index')->with('custom_success', 'Stock Relocation has been Created Successfully !');
    }


    public function edit($id){
        if (!Auth::user()->hasPermissionTo('Stock Relocation Edit')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }
        $stock_relocation = StockRelocation::find($id);
        $stock_relocation_details = StockRelocationDetail::where('stock_relocation_id', $id)->get();
        $locations = AreaLocation::select('area_id', 'rack_id', 'level_id')->with('area', 'rack', 'level')->get();
        return view('wms.operations.stock-reloction.edit',compact('stock_relocation', 'stock_relocation_details', 'locations'));
    }

    public function view($id){
        if (!Auth::user()->hasPermissionTo('Stock Relocation View')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }
        $stock_relocation = StockRelocation::find($id);
        $stock_relocation_details = StockRelocationDetail::where('stock_relocation_id', $id)->get();
        $locations = AreaLocation::select('area_id', 'rack_id', 'level_id')->with('area', 'rack', 'level')->get();
        return view('wms.operations.stock-reloction.view',compact('stock_relocation', 'stock_relocation_details', 'locations'));
    }

    public function update(Request $request,$id)
    {
        if (!Auth::user()->hasPermissionTo('Stock Relocation Edit')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }

        $validated = $request->validate([
            'date' => 'required',
            'ref_no' => 'required',
            'products' => 'required'
        ]);

        foreach($request->products as $value){
            $location = Location::where('area_id', $request->previous_area_id)->where('rack_id', $request->previous_rack_id)->where('level_id', $request->previous_level_id)->where('product_id', $value['product_id'])->first();
            if ($location) {
                if (($location->used_qty - $value['qty']) < 0) {
                    return back()->with('custom_errors', 'Insufficient quantity in previous location!');
                }
            }
        }

        $stock_relocation = StockRelocation::find($id);
        $stock_relocation->date = $request->date;
        $stock_relocation->ref_no = $request->ref_no;
        $stock_relocation->description = $request->description;

        $stock_relocation->previous_area_id = $request->previous_area_id;
        $stock_relocation->previous_rack_id = $request->previous_rack_id;
        $stock_relocation->previous_level_id = $request->previous_level_id;
        $stock_relocation->new_area_id = $request->new_area_id;
        $stock_relocation->new_rack_id = $request->new_rack_id;
        $stock_relocation->new_level_id = $request->new_level_id;

        $stock_relocation->created_by = Auth::user()->id;

        $previousProducts = StockRelocationDetail::where('stock_relocation_id', '=', $stock_relocation->id)->get();
        foreach ($previousProducts as $prevProduct) {
            $prevLocation = Location::where('area_id', $stock_relocation->new_area_id)->where('rack_id', $stock_relocation->new_rack_id)->where('level_id', $stock_relocation->new_level_id)->where('product_id', $prevProduct->product_id)->first();
            if ($prevLocation) {
                $prevLocation->used_qty -= $prevProduct->qty ?? 0;
                $prevLocation->save();
            }

            $newLocation = Location::where('area_id', $stock_relocation->previous_area_id)->where('rack_id', $stock_relocation->previous_rack_id)->where('level_id', $stock_relocation->previous_level_id)->where('product_id', $prevProduct->product_id)->first();
            if ($newLocation) {
                $newLocation->used_qty += $prevProduct->qty ?? 0;
                $newLocation->save();
            }
        }

        $stock_relocation->save();

        StockRelocationDetail::where('stock_relocation_id', '=', $stock_relocation->id)->delete();

        foreach($request->products as $value){
            $stock_relocation_product = new StockRelocationDetail();
            $stock_relocation_product->stock_relocation_id = $stock_relocation->id;
            $stock_relocation_product->product_id = $value['product_id'] ?? null;
            $stock_relocation_product->available_qty = $value['available_qty'] ?? 0;
            $stock_relocation_product->qty = $value['qty'] ?? 0;
            $stock_relocation_product->save();

            $location = Location::where('area_id', $stock_relocation->previous_area_id)->where('rack_id', $stock_relocation->previous_rack_id)->where('level_id', $stock_relocation->previous_level_id)->where('product_id', $stock_relocation_product->product_id)->first();
            if ($location) {
                $location->used_qty -= $stock_relocation_product->qty ?? 0;
                $location->save();
            }

            $location1 = Location::where('area_id', $stock_relocation->new_area_id)->where('rack_id', $stock_relocation->new_rack_id)->where('level_id', $stock_relocation->new_level_id)->where('product_id', $stock_relocation_product->product_id)->first();
            if ($location1) {
                $location1->used_qty += $stock_relocation_product->qty ?? 0;
                if($location1->lot_no != null || $location1->lot_no != ''){
                    $location1->lot_no = $location->lot_no;
                }
            }else{
                $location1 = new Location();
                $location1->area_id = $stock_relocation->new_area_id;
                $location1->rack_id = $stock_relocation->new_rack_id;
                $location1->level_id = $stock_relocation->new_level_id;
                $location1->product_id = $stock_relocation_product->product_id;
                $location1->used_qty = $stock_relocation_product->qty ?? 0;
                $location1->lot_no = $location->lot_no;
            }
            $location1->save();
        }

        return redirect()->route('stock_relocation.index')->with('custom_success', 'Stock Relocation has been Created Successfully !');
    }

    public function destroy($id){
        if (!Auth::user()->hasPermissionTo('Stock Relocation Delete')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }
        $stock_relocation = StockRelocation::find($id);

        $previousProducts = StockRelocationDetail::where('stock_relocation_id', '=', $stock_relocation->id)->get();
        foreach ($previousProducts as $prevProduct) {
            $prevLocation = Location::where('area_id', $stock_relocation->new_area_id)->where('rack_id', $stock_relocation->new_rack_id)->where('level_id', $stock_relocation->new_level_id)->where('product_id', $prevProduct->product_id)->first();
            if ($prevLocation) {
                if (($prevLocation->used_qty - $prevProduct->qty) < 0) {
                    return back()->with('custom_errors', 'Insufficient quantity in location!');
                }
                $prevLocation->used_qty -= $prevProduct->qty ?? 0;
                $prevLocation->save();
            }

            $newLocation = Location::where('area_id', $stock_relocation->previous_area_id)->where('rack_id', $stock_relocation->previous_rack_id)->where('level_id', $stock_relocation->previous_level_id)->where('product_id', $prevProduct->product_id)->first();
            if ($newLocation) {
                $newLocation->used_qty += $prevProduct->qty ?? 0;
                $newLocation->save();
            }
        }

        StockRelocationDetail::where('stock_relocation_id', $id)->delete();
        $stock_relocation->delete();
        return redirect()->route('stock_relocation.index')->with('custom_success', 'Stock Relocation has been Successfully Deleted!');
    }
}
