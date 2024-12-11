<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\AreaRack;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InvertoryShopfloorController extends Controller
{
    public function index()
    {
        if (!Auth::user()->hasPermissionTo('Inventory Shopfloor View')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }
        $areas = DB::table('areas')
            ->select(
                'areas.id as area_id',
                'areas.name as area_name',
                'areas.code as area_code',
                'areas.rack_id as rack_id'
            )
            ->where('areas.department', '!=', 'WIP')
            ->whereNull('areas.deleted_at')
            ->get();

        foreach ($areas as $area) {
            $area->rack_id = json_decode($area->rack_id);
            $area->racks = [];
            $racks = DB::table('area_racks')
                ->select(
                    'area_racks.id as rack_id',
                    'area_racks.name as rack_name',
                    'area_racks.code as rack_code',
                    DB::raw('(SELECT SUM(used_qty) FROM locations WHERE locations.rack_id = area_racks.id AND locations.area_id = ' . $area->area_id . ') as total_quantity'),
                    DB::raw('(SELECT GROUP_CONCAT(DISTINCT product_id) FROM locations WHERE locations.rack_id = area_racks.id AND locations.area_id = ' . $area->area_id . ') as product_ids')
                )
                ->whereIn('area_racks.id', $area->rack_id)
                ->whereNull('area_racks.deleted_at')
                ->get();

            $area->total_quantity = 0;
            $area->price = 0;

            foreach ($racks as $rack) {
                $productIds = explode(',', $rack->product_ids);
                $totalPrice = DB::table('purchase_prices as pp')
                    ->select(DB::raw('SUM(pp.price) as total_price'))
                    ->whereIn('pp.product_id', $productIds)
                    ->where('pp.status', 'verified')
                    ->whereRaw('pp.date = (
                        SELECT MAX(date)
                        FROM purchase_prices
                        WHERE product_id = pp.product_id AND status = "verified"
                    )')
                    ->value('total_price');

                $area->racks[] = $rack;
                $rack->price = $totalPrice * $rack->total_quantity;
                $area->total_quantity += $rack->total_quantity;
                $area->price += $totalPrice * $rack->total_quantity;
            }
        }
        return view("wms.dashboard.inventory-shopfloor.index", compact('areas'));
    }

    public function generate(Request $request)
    {
        $racks = AreaRack::find($request->rack_id);
        $racks->level_id = json_decode($racks->level_id);
        $levels = DB::table('area_levels')
            ->select(
                'area_levels.id as level_id',
                'area_levels.name as level_name',
                'area_levels.code as level_code',
                DB::raw('(SELECT SUM(used_qty) FROM locations WHERE locations.level_id = area_levels.id AND locations.area_id = ' . $request->area_id . ' AND locations.rack_id = ' . $request->rack_id . ') as total_quantity'),
                DB::raw('(SELECT GROUP_CONCAT(DISTINCT product_id) FROM locations WHERE locations.level_id = area_levels.id AND locations.area_id = ' . $request->area_id . ' AND locations.rack_id = ' . $request->rack_id . ') as product_ids')
            )
            ->whereIn('area_levels.id', $racks->level_id)
            ->whereNull('area_levels.deleted_at')
            ->get();
        foreach ($levels as $level) {
            $productIds = explode(',', $level->product_ids);
            $totalPrice = DB::table('purchase_prices as pp')
                ->select(DB::raw('SUM(pp.price) as total_price'))
                ->whereIn('pp.product_id', $productIds)
                ->where('pp.status', 'verified')
                ->whereRaw('pp.date = (
                        SELECT MAX(date)
                        FROM purchase_prices
                        WHERE product_id = pp.product_id AND status = "verified"
                    )')
                ->value('total_price');

            $level->price = $totalPrice * $level->total_quantity;
        }
        return $levels;
    }

    public function generate2(Request $request)
    {
        $details = Location::where('area_id', $request->area_id)->where('rack_id', $request->rack_id)->where('level_id', $request->level_id)->where('used_qty', '!=', 0)->with('product')->get();
        foreach ($details as $detail) {
            $latestPrice = DB::table('purchase_prices')
                ->where('product_id', $detail->product_id)
                ->where('status', 'verified')
                ->orderBy('date', 'desc')
                ->value('price');

            $detail->price = $latestPrice * $detail->used_qty;
        }
        return $details;
    }
}
