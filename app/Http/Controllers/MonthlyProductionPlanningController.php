<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\DeliveryInstruction;
use App\Models\Location;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MonthlyProductionPlanningController extends Controller
{
    public function monthly_production_planning(){

        if (!Auth::user()->hasPermissionTo('Monthly Production Planning')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $orders = Order::all();
        $customers = Customer::all();
        return view('mes.ppc.monthly_production_planning', compact('orders','customers'));
    }

    public function generate(Request $request){
        $orders = Order::all();
        $customers = Customer::all();
        $order_id = $request->order_id;
        $customer_id = $request->customer_id;
        $order_month = $request->order_month;


        $fetched_orders = Order::with('order_detail.products');

        if (!is_null($order_id)) {
            $fetched_orders->where('id', $order_id);
        }

        if (!is_null($customer_id)) {
            $fetched_orders->where('customer_id', $customer_id);
        }

        if (!is_null($order_month)) {
            $fetched_orders->where('order_month', $order_month);
        }

        $fetched_orders = $fetched_orders->get();

        if($fetched_orders->isEmpty()) {
            return redirect()->route('ppc.monthly_production_planning')->with('custom_errors', 'No orders found for the given criteria');
        }
        $product_ids = $fetched_orders[0]->order_detail->pluck('product_id');

        $inventory = DB::table('locations')
            ->whereIn('product_id', $product_ids)
            ->select('product_id', DB::raw('SUM(used_qty) as qty'))
            ->groupBy('product_id')
            ->get();
        $delievry_intructions = DeliveryInstruction::with('delivery_instruction_details','order')->where('order_id','=',$fetched_orders[0]->id)->get();
        return view('mes.ppc.monthly_production_planning', compact('orders','customers','fetched_orders','inventory','delievry_intructions'));

    }
}
