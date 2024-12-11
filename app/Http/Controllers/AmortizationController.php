<?php

namespace App\Http\Controllers;

use App\Models\Amortization;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AmortizationController extends Controller
{
    public function edit($id){
        // if (!Auth::user()->hasPermissionTo('Product Amortization Edit')) {
        //     return back()->with(
        //         'custom_errors',
        //         'You don`t have the right permission'
        //     );
        // }
        $amortization = Amortization::where('product_id',$id)->first();
        $units = Unit::all();
        return view('database.product.amortization', compact('amortization','units','id'));
    }
    public function update(Request $request, $id){
        if($id == 0){
            $amortization = new Amortization();
            $amortization->product_id = $request->product_id;
            $amortization->amortization_qty = $request->amortization_qty;
            $amortization->delivered_qty = $request->delivered_qty;
            $amortization->opening_delivered_qty = $request->opening_delivered_qty;
            $amortization->balance_amortization = $request->balance_amortization;
            $amortization->start_date = $request->start_date;
            $amortization->amortization_period = $request->amortization_period;
            $amortization->unit_id = $request->unit;
            $amortization->save();
            return redirect()->route('product.index')->with('custom_success', 'Amortization Created Successfully.');
        }else{
            $amortization = Amortization::find($id);
            $amortization->product_id = $request->product_id;
            $amortization->amortization_qty = $request->amortization_qty;
            $amortization->delivered_qty = $request->delivered_qty;
            $amortization->opening_delivered_qty = $request->opening_delivered_qty;
            $amortization->balance_amortization = $request->balance_amortization;
            $amortization->start_date = $request->start_date;
            $amortization->amortization_period = $request->amortization_period;
            $amortization->unit_id = $request->unit;
            $amortization->save();
            return redirect()->route('product.index')->with('custom_success', 'Amortization Updated Successfully.');
        }
    }
}
