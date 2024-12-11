<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StockAdjustmentController extends Controller
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

            $query = Product::with(['locations' => function($query) {
                $query->select('product_id', DB::raw('SUM(used_qty) as total_used_qty'))
                      ->groupBy('product_id');
            }]);

            // Apply search if a search term is provided
            if (!empty($search)) {
                $searchLower = strtolower($search);
                $query->where(function ($q) use ($searchLower) {
                    $q

                        ->where('part_no', 'like', '%' . $searchLower . '%')
                        ->orWhere('part_name', 'like', '%' . $searchLower . '%')
                        ->orWhereHas('locations', function ($query) use ($searchLower) {
                            $query->having(DB::raw('SUM(used_qty)'), 'like', '%' . $searchLower . '%');
                        });

                });
            }
            $results = null;

            if (!empty($columnsData)) {

                $sortableColumns = [
                    1 => 'part_no',
                    2 => 'part_name',
                    3 => 'locations',


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
                                $q->where('part_no', 'like', '%' . $searchLower . '%');

                                break;
                            case 2:
                                $q->where('part_name', 'like', '%' . $searchLower . '%');



                                break;
                                case 3:
                                    $q ->whereHas('locations', function ($query) use ($searchLower) {
                                        $query->having(DB::raw('SUM(used_qty)'), 'like', '%' . $searchLower . '%');
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

               $row->location_quantity = $row->locations->first()->total_used_qty ?? 0;

                $row->action .= ' <a class="btn btn-success btn-sm" href="#"
                                        onclick="render_locations(' . $row->id . ', \'' . $row->part_no . '\', \'' . $row->part_name . '\')">
                                        <i class="bi bi-sliders"></i>
                                    </a>';
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

            $query = Product::with(['locations' => function($query) {
                $query->select('product_id', DB::raw('SUM(used_qty) as total_used_qty'))
                      ->groupBy('product_id');
            }]);


            // Apply search if a search term is provided
            if (!empty($search)) {
                $searchLower = strtolower($search);
                $query->where(function ($q) use ($searchLower) {
                    $q

                        ->where('part_no', 'like', '%' . $searchLower . '%')
                        ->orWhere('part_name', 'like', '%' . $searchLower . '%')
                        ->orWhereHas('locations', function ($query) use ($searchLower) {
                            $query->having(DB::raw('SUM(used_qty)'), 'like', '%' . $searchLower . '%');
                        });

                });
            }
            $sortableColumns = [
                1 => 'part_no',
                2 => 'part_name',
                3 => 'locations',


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
                $row->location_quantity = $row->locations->first()->total_used_qty ?? 0;

                $row->action .= ' <a class="btn btn-success btn-sm" href="#"
                                        onclick="render_locations(' . $row->id . ', \'' . $row->part_no . '\', \'' . $row->part_name . '\')">
                                        <i class="bi bi-sliders"></i>
                                    </a>';



            });

            return response()->json([
                'draw' => $draw,
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsTotal, // Total records after filtering
                'data' => $uom,
            ]);
        }
    }
    public function index()
    {
        if (
            Auth::user()->hasPermissionTo('Stock Adjustment List') ||
            Auth::user()->hasPermissionTo('Stock Adjustment Update')
        ) {
            $products = Product::with(['locations' => function($query) {
                $query->select('product_id', DB::raw('SUM(used_qty) as total_used_qty'))
                      ->groupBy('product_id');
            }])->get();
            return view('wms.operations.stock-adjustment.index', compact('products'));
        }
        return back()->with('custom_errors', 'You don`t have the right permission');
    }

    public function edit($id)
    {
        $locations = Location::where('product_id', $id)->with('area', 'rack', 'level')->get();
        return response()->json($locations);
    }

    public function update(Request $request, $id)
    {
        if (!Auth::user()->hasPermissionTo('Stock Adjustment Update')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }

        foreach ($request->quantities as $locationId => $quantity) {
            $location = Location::find($locationId);
            if ($location) {
                $location->used_qty += $quantity; // This handles both addition and subtraction
                if ($location->used_qty < 0) {
                    $location->used_qty = 0; // Prevent negative values
                }
                $location->save();
            }
        }
        

        NotificationController::Notification(
            'Stock Adjustment',
            'Update',
            route('stock_adjustment.index')
        );


        return redirect()->route('stock_adjustment.index')->with('custom_success', 'Stock Adjustment has been Updated Successfully!');
    }
}
