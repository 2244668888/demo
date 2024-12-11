<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvertoryDashboardController extends Controller
{
    public function index(){
        if (!Auth::user()->hasPermissionTo('Inventory Dashboard View')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }
        $products = Product::select('id', 'part_no', 'part_name')->with('reordering', 'location')->get();
        return view("wms.dashboard.inventory-dashboard.index", compact('products'));
    }
}
