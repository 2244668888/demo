<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Outgoing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SummaryDoReportController extends Controller
{
    public function index(){
        if (!Auth::user()->hasPermissionTo('Summary DO Report View')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }
        $outgoings = Outgoing::select('id', 'ref_no', 'category', 'sr_id', 'pr_id', 'order_id')->with('sales_return', 'purchase_return', 'order')->get();
        $outgoings = $outgoings->map(function($outgoing) {
            return [
                'id' => $outgoing->id,
                'ref_no' => $outgoing->ref_no,
                'category' => $outgoing->category,
                'sales_return_id' => $outgoing->sr_id,
                'purchase_return_id' => $outgoing->pr_id,
                'order_id' => $outgoing->order_id,
                'sales_return_ref_no' => $outgoing->sales_return ? $outgoing->sales_return->ref_no : 'N/A',
                'purchase_return_ref_no' => $outgoing->purchase_return ? $outgoing->purchase_return->grd_no : 'N/A',
                'order_ref_no' => $outgoing->order ? $outgoing->order->order_no : 'N/A',
            ];
        });
        return view("wms.report.summary-do-report.index", compact('outgoings'));
    }

    public function generate(Request $request){
        $start_date = null;
        $end_date = null;
        if($request->start_date && $request->end_date){
            $start_date = $request->start_date;
            $end_date = $request->end_date;
        }
        
        $outgoings = Outgoing::select('id', 'date', 'ref_no', 'category', 'sr_id', 'pr_id', 'order_id');

        if($request->id){
            $outgoings = $outgoings->whereIn('id', $request->id);
        }

        if($start_date && $end_date){
            $outgoings = $outgoings->whereDate('date', '>=', $start_date)->whereDate('date', '<=', $end_date);
        }

        if($request->category){
            $outgoings = $outgoings->whereIn('category', $request->category);
            if($request->ref_no){
                if($request->category == 1){
                    $outgoings = $outgoings->whereIn('sr_id', $request->ref_no);
                }else if($request->category == 2){
                    $outgoings = $outgoings->whereIn('pr_id', $request->ref_no);
                }else if($request->category == 3){
                    $outgoings = $outgoings->whereIn('order_id', $request->ref_no);
                }
            }
        }

        $outgoings = $outgoings->with('sales_return', 'purchase_return', 'order', 'outgoing_detail.product')->get();
        return response()->json($outgoings);
    }
}
