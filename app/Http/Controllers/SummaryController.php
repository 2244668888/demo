<?php

namespace App\Http\Controllers;

use App\Models\Machine;
use App\Models\MachineApi;
use App\Models\MachinePreperation;
use App\Models\Product;
use App\Models\ProductionApi;
use App\Models\ProductionOutputTraceability;
use App\Models\ProductionOutputTraceabilityDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SummaryController extends Controller
{
    public function index()
    {
        if (Auth::user()->hasPermissionTo('Summary Report View')) {
            $machines = Machine::select('id', 'name', 'code')->get();
            $products = Product::select('id', 'part_no', 'part_name')->get();
            return view('mes.production.summary-report.index', compact('machines', 'products'));
        }
        return back()->with('custom_errors', 'You don`t have the right permission');
    }

    public function generate(Request $request)
    {
        $start_date = Carbon::now('Asia/Kuala_Lumpur')->startOfDay();
        $end_date = Carbon::now('Asia/Kuala_Lumpur')->endOfDay();
        $acs_desc = $request->asc_desc;

        if ($request->start_date) {
            $start_date = Carbon::parse($request->start_date)->format('d-m-Y h:i:s A');
            $end_date = Carbon::parse($request->end_date)->format('d-m-Y h:i:s A');
        }

        $summary = array();
        if ($request->machine_product == 1) {
            $products = Product::select('id', 'part_no', 'part_name');
            if ($request->products) {
                $products = $products->whereIn('id', $request->products);
            } else {
                $products = $products->limit(10);
            }
            $products = $products->orderBy('part_no', $acs_desc)->get();

            foreach ($products as $product) {
                //production time
                $details = ProductionOutputTraceabilityDetail::join('production_output_traceabilities', 'production_output_traceability_details.pot_id', '=', 'production_output_traceabilities.id')
                    ->select(DB::raw("SUM(TIMESTAMPDIFF(HOUR, start_time, end_time)) AS production_time"), 'production_output_traceability_details.machine_id', 'production_output_traceability_details.start_time as start_time', 'production_output_traceability_details.end_time as end_time')
                    ->where('production_output_traceabilities.product_id', $product->id)
                    ->where('production_output_traceability_details.start_time', '>=', $start_date)
                    ->where(function ($query) use ($end_date) {
                        $query->where('production_output_traceability_details.end_time', '<=', $end_date)
                            ->orWhereNull('production_output_traceability_details.end_time');
                    })
                    ->groupBy('production_output_traceability_details.machine_id', 'production_output_traceability_details.start_time', 'production_output_traceability_details.end_time')
                    ->get();

                $production_time_hr = $details->sum('production_time');
                $production_time_percentage = 0;
                $down_time_hr = 0;
                $down_time_percentage = 0;
                $setup_time_hr = 0;
                $setup_time_percentage = 0;
                $setup_frequency = 0;
                $rejected_pcss = 0;
                $rejected_percentage = 0;
                $rejected_kg = 0;
                $planned_qty = 0;
                $actual_qty = 0;
                foreach ($details as $detail) {
                    $machine = Machine::find($detail->machine_id);
                    
                    //down time
                    $actual_running_time = MachineApi::select(DB::raw("
                    SUM(TIMESTAMPDIFF(HOUR,
                        STR_TO_DATE(start_time, '%d-%m-%Y %H:%i:%s'),
                        COALESCE(STR_TO_DATE(end_time, '%d-%m-%Y %H:%i:%s'), NOW())
                    )) as actual_running_time
                    "))->where('mc_no', $machine->code)->where('start_time', '>=', $detail->start_time)->where(function ($query) use ($end_date) {
                        $query->where('end_time', '<=', $end_date)
                            ->orWhereNull('end_time');
                    })->first();

                    $down_time_hr += $production_time_hr - ($actual_running_time->actual_running_time ?? 0);

                    //setup time
                    $machine_setup = MachinePreperation::select(DB::raw("
                    SUM(TIMESTAMPDIFF(HOUR,
                        STR_TO_DATE(start_time, '%d-%m-%Y %H:%i:%s'),
                        COALESCE(STR_TO_DATE(end_time, '%d-%m-%Y %H:%i:%s'), NOW())
                    )) as machine_setup
                    "))->where('mc_no', $machine->code)->where('start_time', '>=', $detail->start_time)->where(function ($query) use ($end_date) {
                        $query->where('end_time', '<=', $end_date)
                            ->orWhereNull('end_time');
                    })->first();

                    $setup_time_hr += $machine_setup->machine_setup;

                    // Production time percentage
                    $production_time_percentage += $production_time_hr > 0 ? ($production_time_hr / ($production_time_hr + $down_time_hr + $setup_time_hr)) * 100 : 0;

                    // Down time percentage
                    $down_time_percentage += $down_time_hr > 0 ? ($down_time_hr / ($production_time_hr + $down_time_hr + $setup_time_hr)) * 100 : 0;

                    // Setup time percentage
                    $setup_time_percentage += $setup_time_hr > 0 ? ($setup_time_hr / ($production_time_hr + $down_time_hr + $setup_time_hr)) * 100 : 0;

                    //setup frequency
                    $setup_frequency += MachinePreperation::where('mc_no', $machine->code)->where('start_time', '>=', $detail->start_time)->where('end_time', '<=', $detail->end_time)->count();

                    // Rejected pcs
                    $rejected_pcs = ProductionOutputTraceabilityDetail::join('production_output_traceabilities', 'production_output_traceability_details.pot_id', '=', 'production_output_traceabilities.id')
                        ->join('products', 'production_output_traceabilities.product_id', '=', 'products.id')
                        ->select(DB::raw("SUM(production_output_traceabilities.total_rejected_qty) as rejected_pcs"), DB::raw("SUM(production_output_traceabilities.qc_rejected_qty) as qc_rejected_pcs"), DB::raw("SUM(production_output_traceabilities.total_produced) as total_produced"), DB::raw("SUM(products.part_weight) as part_weight"), DB::raw("SUM(production_output_traceabilities.planned_qty) as planned_qty"))
                        ->where('production_output_traceability_details.start_time', '>=', $start_date)
                        ->where(function ($query) use ($end_date) {
                            $query->where('production_output_traceability_details.end_time', '<=', $end_date)
                                ->orWhereNull('production_output_traceability_details.end_time');
                        })
                        ->where('production_output_traceabilities.product_id', '=', $product->id)
                        ->first();

                    $rejected_pcss += $rejected_pcs->rejected_pcs + $rejected_pcs->qc_rejected_pcs ?? 0;

                    // Rejected percentage
                    $rejected_percentage += $rejected_pcs->rejected_pcs > 0 ? ($rejected_pcs->rejected_pcs / $rejected_pcs->total_produced) * 100 : 0;

                    // Rejected kg
                    $rejected_kg += $rejected_pcs->part_weight * $rejected_pcs->rejected_pcs;

                    $planned_qty += $rejected_pcs->planned_qty ?? 0;
                    $actual_qty += $rejected_pcs->total_produced ?? 0;
                }

                // Putting in array
                $summary[$product->part_name]['production_time_hr'] = $production_time_hr ?? 0;
                $summary[$product->part_name]['production_time_percentage'] = $production_time_percentage ?? 0;
                $summary[$product->part_name]['down_time_hr'] = $down_time_hr ?? 0;
                $summary[$product->part_name]['down_time_percentage'] = $down_time_percentage ?? 0;
                $summary[$product->part_name]['setup_time_hr'] = $setup_time_hr ?? 0;
                $summary[$product->part_name]['setup_time_percentage'] = $setup_time_percentage ?? 0;
                $summary[$product->part_name]['setup_frequency'] = $setup_frequency ?? 0;
                $summary[$product->part_name]['rejected_pcs'] = $rejected_pcss ?? 0;
                $summary[$product->part_name]['rejected_percentage'] = $rejected_percentage ?? 0;
                $summary[$product->part_name]['rejected_kg'] = $rejected_kg ?? 0;
                $summary[$product->part_name]['planned_qty'] = $planned_qty ?? 0;
                $summary[$product->part_name]['actual_qty'] = $actual_qty ?? 0;
            }
        } else {
            $machines = Machine::select('id', 'name', 'code');
            if ($request->machines) {
                $machines = $machines->whereIn('id', $request->machines);
            } else {
                $machines = $machines->limit(10);
            }
            $machines = $machines->orderBy('name', $acs_desc)->get();

            foreach ($machines as $machine) {
                //production time
                $production = ProductionApi::select(DB::raw("
                SUM(TIMESTAMPDIFF(HOUR,
                    STR_TO_DATE(start_time, '%d-%m-%Y %H:%i:%s'),
                    COALESCE(STR_TO_DATE(end_time, '%d-%m-%Y %H:%i:%s'), NOW())
                )) as production
                "))->where('mc_no', $machine->code)->where('start_time', '>=', $start_date)->where(function ($query) use ($end_date) {
                    $query->where('end_time', '<=', $end_date)
                        ->orWhereNull('end_time');
                })->first();

                $production_time_hr = $production->production;

                //down time
                $actual_running_time = MachineApi::select(DB::raw("
                SUM(TIMESTAMPDIFF(HOUR,
                    STR_TO_DATE(start_time, '%d-%m-%Y %H:%i:%s'),
                    COALESCE(STR_TO_DATE(end_time, '%d-%m-%Y %H:%i:%s'), NOW())
                )) as actual_running_time
                "))->where('mc_no', $machine->code)->where('start_time', '>=', $start_date)->where(function ($query) use ($end_date) {
                    $query->where('end_time', '<=', $end_date)
                        ->orWhereNull('end_time');
                })->first();

                $down_time_hr = ($actual_running_time->actual_running_time ?? 0) - $production_time_hr;
                // dd($production_time_hr);

                //setup time
                $machine_setup = MachinePreperation::select(DB::raw("
                SUM(TIMESTAMPDIFF(HOUR,
                    STR_TO_DATE(start_time, '%d-%m-%Y %H:%i:%s'),
                    COALESCE(STR_TO_DATE(end_time, '%d-%m-%Y %H:%i:%s'), NOW())
                )) as machine_setup
                "))->where('mc_no', $machine->code)->where('start_time', '>=', $start_date)->where(function ($query) use ($end_date) {
                    $query->where('end_time', '<=', $end_date)
                        ->orWhereNull('end_time');
                })->first();

                $setup_time_hr = $machine_setup->machine_setup;

                //production time %
                $production_time_percentage = $production_time_hr > 0 ? ($production_time_hr / $production_time_hr + $down_time_hr + $setup_time_hr) * 100 : 0;
                $production_time_percentage = min($production_time_percentage, 100);
                //down time %
                $down_time_percentage = ($production_time_hr > 0 && $down_time_hr > 0) ? ($down_time_hr / $production_time_hr + $down_time_hr + $setup_time_hr) * 100 : 0;
                $down_time_percentage = min($down_time_percentage, 100);
                // dd(($down_time_hr / $production_time_hr + $down_time_hr + $setup_time_hr));
                //setup time %
                $setup_time_percentage = $production_time_hr > 0 ? ($setup_time_hr / $production_time_hr + $down_time_hr + $setup_time_hr) * 100 : 0;
                $setup_time_percentage = min($setup_time_percentage, 100);
                //setup frequency
                $setup_frequency = MachinePreperation::where('mc_no', $machine->code)->where('start_time', '>=', $start_date)->where(function ($query) use ($end_date) {
                    $query->where('end_time', '<=', $end_date)
                        ->orWhereNull('end_time');
                })->count();

                // rejected pc
                $rejected_pcs = ProductionOutputTraceabilityDetail::leftJoin('production_output_traceabilities', 'production_output_traceability_details.pot_id', '=', 'production_output_traceabilities.id')
                    ->join('products', 'production_output_traceabilities.product_id', '=', 'products.id')
                    ->select(
                        DB::raw("SUM(DISTINCT production_output_traceabilities.total_rejected_qty) as rejected_pcs"),
                        DB::raw("SUM(DISTINCT production_output_traceabilities.qc_rejected_qty) as qc_rejected_pcs"),
                        DB::raw("SUM(DISTINCT production_output_traceabilities.total_produced) as total_produced"),
                        DB::raw("SUM(DISTINCT products.part_weight) as part_weight"),
                        DB::raw("SUM(DISTINCT production_output_traceabilities.planned_qty) as planned_qty")
                    )
                    ->where('production_output_traceability_details.start_time', '>=', $start_date)
                    ->where(function ($query) use ($end_date) {
                        $query->where('production_output_traceability_details.end_time', '<=', $end_date)
                            ->orWhereNull('production_output_traceability_details.end_time');
                    })
                    ->where('production_output_traceability_details.machine_id', '=', $machine->id)
                    ->first();
                // dd($rejected_pcs);
                // rejected %
                $rejected_percentage = $rejected_pcs->rejected_pcs > 0 ? ($rejected_pcs->rejected_pcs / $rejected_pcs->total_produced) * 100 : 0;

                // rejected kg
                $rejected_kg = $rejected_pcs->part_weight * $rejected_pcs->rejected_pcs;

                //putting in array
                $summary[$machine->name]['production_time_hr'] = $production_time_hr ?? 0;
                $summary[$machine->name]['production_time_percentage'] = $production_time_percentage ?? 0;
                $summary[$machine->name]['down_time_hr'] = $down_time_hr ?? 0;
                $summary[$machine->name]['down_time_percentage'] = $down_time_percentage ?? 0;
                $summary[$machine->name]['setup_time_hr'] = $setup_time_hr ?? 0;
                $summary[$machine->name]['setup_time_percentage'] = $setup_time_percentage ?? 0;
                $summary[$machine->name]['setup_frequency'] = $setup_frequency ?? 0;
                $summary[$machine->name]['rejected_pcs'] = $rejected_pcs->rejected_pcs + $rejected_pcs->qc_rejected_pcs ?? 0;
                $summary[$machine->name]['rejected_percentage'] = $rejected_percentage ?? 0;
                $summary[$machine->name]['rejected_kg'] = $rejected_kg ?? 0;
                $summary[$machine->name]['planned_qty'] = $rejected_pcs->planned_qty ?? 0;
                $summary[$machine->name]['actual_qty'] = $rejected_pcs->total_produced ?? 0;

                // $summary[$machine->name]['production_time_hr'] = 20;
                // $summary[$machine->name]['production_time_percentage'] = 10;
                // $summary[$machine->name]['down_time_hr'] = 30;
                // $summary[$machine->name]['down_time_percentage'] = 40;
                // $summary[$machine->name]['setup_time_hr'] = 50;
                // $summary[$machine->name]['setup_time_percentage'] = 60;
                // $summary[$machine->name]['setup_frequency'] = 70;
                // $summary[$machine->name]['rejected_pcs'] = 80;
                // $summary[$machine->name]['rejected_percentage'] = 90;
                // $summary[$machine->name]['rejected_kg'] = 100;
                // $summary[$machine->name]['planned_qty'] = 110;
                // $summary[$machine->name]['actual_qty'] = 220;
            }
        }

        return response()->json($summary);
    }
}
