<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\AreaLevel;
use App\Models\AreaRack;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Location;

class InventoryReportController extends Controller
{
    public function index(){
        if (!Auth::user()->hasPermissionTo('Inventory Report View')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }
        $products = Product::all();
        $areas = Area::select('id', 'name')->get();
        $racks = AreaRack::select('id', 'name')->get();
        $levels = AreaLevel::select('id', 'name')->get();
        return view("wms.report.inventory-report.index", compact('products', 'areas', 'racks', 'levels'));
    }

    public function generate(Request $request){
        $location = Location::select('area_id', 'rack_id', 'level_id', 'product_id', 'used_qty', 'lot_no');
        if(isset($request->area_id)){
            $location = $location->whereIn('area_id', $request->area_id);
        }
        if(isset($request->rack_id)){
            $location = $location->whereIn('rack_id', $request->rack_id);
        }
        if(isset($request->level_id)){
            $location = $location->whereIn('level_id', $request->level_id);
        }
        if(isset($request->product_id)){
            $location = $location->where('product_id', $request->product_id);
        }
        $location = $location->with('area', 'rack', 'level', 'product', 'product.units')->get();
        return response()->json($location);
    }
}
