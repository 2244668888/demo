<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Product;
use Carbon\Carbon;

class StockCardReportController extends Controller
{
    public function index()
    {
        if (!Auth::user()->hasPermissionTo('Stock Card Report View')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }
        $products = Product::select('id', 'part_no', 'part_name')->get();
        return view("wms.report.stock-card-report.index", compact('products'));
    }

    public function generate(Request $request)
    {
        $start_date = Carbon::parse($request->start_date)->format('Y-m-d');
        $end_date = Carbon::parse($request->end_date)->format('Y-m-d');

        $good_receiving = DB::table('good_receivings')
            ->select('good_receivings.date as date', 'good_receivings.ref_no as ref_no', 'products.part_no as part_no', 'products.part_name as part_name', 'units.name as unit', 'good_receiving_products.accepted_qty as qty')
            ->join('good_receiving_products', 'good_receivings.id', '=', 'good_receiving_products.gr_id')
            ->join('products', 'good_receiving_products.product_id', '=', 'products.id')
            ->join('units', 'products.unit', '=', 'units.id')
            ->where('good_receiving_products.product_id', '=', $request->product)
            ->whereBetween('good_receivings.date', [$start_date, $end_date])
            ->get()
            ->map(function ($item) {
                $item->type = 'Good Receiving';
                $item->sign = '+';
                return $item;
            });
        $material_requisition_issue = DB::table('material_requisitions')
            ->select('material_requisitions.issue_date as date', 'material_requisitions.ref_no as ref_no', 'products.part_no as part_no', 'products.part_name as part_name', 'units.name as unit', 'material_requisition_details.issue_qty as qty')
            ->join('material_requisition_details', 'material_requisition_details.material_requisition_id', '=', 'material_requisitions.id')
            ->join('products', 'material_requisition_details.product_id', '=', 'products.id')
            ->join('units', 'products.unit', '=', 'units.id')
            ->where('material_requisition_details.product_id', '=', $request->product)
            ->whereBetween('material_requisitions.issue_date', [$start_date, $end_date])
            ->get()
            ->map(function ($item) {
                $item->type = 'Material Requisition';
                $item->sign = '-';
                return $item;
            });
        $material_requisition_receive = DB::table('material_requisitions')
            ->select('material_requisitions.rcv_date as date', 'material_requisitions.ref_no as ref_no', 'products.part_no as part_no', 'products.part_name as part_name', 'units.name as unit', 'material_requisition_details.rcv_qty as qty')
            ->join('material_requisition_details', 'material_requisition_details.material_requisition_id', '=', 'material_requisitions.id')
            ->join('products', 'material_requisition_details.product_id', '=', 'products.id')
            ->join('units', 'products.unit', '=', 'units.id')
            ->where('material_requisition_details.product_id', '=', $request->product)
            ->whereBetween('material_requisitions.rcv_date', [$start_date, $end_date])
            ->get()
            ->map(function ($item) {
                $item->type = 'Material Requisition';
                $item->sign = '+';
                return $item;
            });
        $transfer_request_issue = DB::table('transfer_requests')
            ->select('transfer_requests.issue_date as date', 'transfer_requests.ref_no as ref_no', 'products.part_no as part_no', 'products.part_name as part_name', 'units.name as unit', 'transfer_request_details.issue_qty as qty')
            ->join('transfer_request_details', 'transfer_request_details.transfer_request_id', '=', 'transfer_requests.id')
            ->join('products', 'transfer_request_details.product_id', '=', 'products.id')
            ->join('units', 'products.unit', '=', 'units.id')
            ->where('transfer_request_details.product_id', '=', $request->product)
            ->whereBetween('transfer_requests.issue_date', [$start_date, $end_date])
            ->get()
            ->map(function ($item) {
                $item->type = 'Transfer Request';
                $item->sign = '-';
                return $item;
            });
        $transfer_request_receive = DB::table('transfer_requests')
            ->select('transfer_requests.rcv_date as date', 'transfer_requests.ref_no as ref_no', 'products.part_no as part_no', 'products.part_name as part_name', 'units.name as unit', 'transfer_request_details.rcv_qty as qty')
            ->join('transfer_request_details', 'transfer_request_details.transfer_request_id', '=', 'transfer_requests.id')
            ->join('products', 'transfer_request_details.product_id', '=', 'products.id')
            ->join('units', 'products.unit', '=', 'units.id')
            ->where('transfer_request_details.product_id', '=', $request->product)
            ->whereBetween('transfer_requests.rcv_date', [$start_date, $end_date])
            ->get()
            ->map(function ($item) {
                $item->type = 'Transfer Request';
                $item->sign = '+';
                return $item;
            });
        $discrepancies_issue = DB::table('discrepancies')
            ->select('discrepancies.date as date', 'discrepancies.ref_no as ref_no', 'products.part_no as part_no', 'products.part_name as part_name', 'units.name as unit', 'discrepancies.issue_qty as qty')
            ->join('products', 'discrepancies.product_id', '=', 'products.id')
            ->join('units', 'products.unit', '=', 'units.id')
            ->where('discrepancies.product_id', '=', $request->product)
            ->whereBetween('discrepancies.date', [$start_date, $end_date])
            ->get()
            ->map(function ($item) {
                $item->type = 'Discrepancy';
                $item->sign = '-';
                return $item;
            });
        $discrepancies_receive = DB::table('discrepancies')
            ->select('discrepancies.date as date', 'discrepancies.ref_no as ref_no', 'products.part_no as part_no', 'products.part_name as part_name', 'units.name as unit', 'discrepancies.rcv_qty as qty')
            ->join('products', 'discrepancies.product_id', '=', 'products.id')
            ->join('units', 'products.unit', '=', 'units.id')
            ->where('discrepancies.product_id', '=', $request->product)
            ->whereBetween('discrepancies.date', [$start_date, $end_date])
            ->get()
            ->map(function ($item) {
                $item->type = 'Discrepancy';
                $item->sign = '+';
                return $item;
            });
        $sales_return = DB::table('sales_returns')
            ->select('sales_returns.date as date', 'sales_returns.ref_no as ref_no', 'products.part_no as part_no', 'products.part_name as part_name', 'units.name as unit', 'sales_return_details.qty as qty')
            ->join('sales_return_details', 'sales_return_details.sales_return_id', '=', 'sales_returns.id')
            ->join('products', 'sales_return_details.product_id', '=', 'products.id')
            ->join('units', 'products.unit', '=', 'units.id')
            ->where('sales_return_details.product_id', '=', $request->product)
            ->whereBetween('sales_returns.date', [$start_date, $end_date])
            ->get()
            ->map(function ($item) {
                $item->type = 'Sales Return';
                $item->sign = '+';
                return $item;
            });
        $purchase_return = DB::table('purchase_returns')
            ->select('purchase_returns.date as date', 'purchase_returns.grd_no as ref_no', 'products.part_no as part_no', 'products.part_name as part_name', 'units.name as unit', 'purchase_return_products.qty as qty')
            ->join('purchase_return_products', 'purchase_return_products.purchase_return_id', '=', 'purchase_returns.id')
            ->join('products', 'purchase_return_products.product_id', '=', 'products.id')
            ->join('units', 'products.unit', '=', 'units.id')
            ->where('purchase_return_products.product_id', '=', $request->product)
            ->whereBetween('purchase_returns.date', [$start_date, $end_date])
            ->get()
            ->map(function ($item) {
                $item->type = 'Purchase Return';
                $item->sign = '-';
                return $item;
            });
        $outgoing = DB::table('outgoings')
            ->select('outgoings.date as date', 'outgoings.ref_no as ref_no', 'products.part_no as part_no', 'products.part_name as part_name', 'units.name as unit', 'outgoing_details.qty as qty')
            ->join('outgoing_details', 'outgoing_details.outgoing_id', '=', 'outgoings.id')
            ->join('products', 'outgoing_details.product_id', '=', 'products.id')
            ->join('units', 'products.unit', '=', 'units.id')
            ->where('outgoing_details.product_id', '=', $request->product)
            ->whereBetween('outgoings.date', [$start_date, $end_date])
            ->get()
            ->map(function ($item) {
                $item->type = 'Outgoing';
                $item->sign = '-';
                return $item;
            });

        $all_data = collect($good_receiving)
            ->merge($material_requisition_issue)
            ->merge($material_requisition_receive)
            ->merge($transfer_request_issue)
            ->merge($transfer_request_receive)
            ->merge($discrepancies_issue)
            ->merge($discrepancies_receive)
            ->merge($sales_return)
            ->merge($purchase_return)
            ->merge($outgoing);

        $sorted_data = $all_data->sortByDesc('date')->values();

        return response()->json($sorted_data);
    }
}
