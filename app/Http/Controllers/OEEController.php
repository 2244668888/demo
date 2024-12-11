<?php

namespace App\Http\Controllers;

use App\Models\CallForAssistance;
use Carbon\Carbon;
use App\Models\Machine;
use App\Models\SpecBreak;
use App\Models\MachineApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\MachinePreperation;
use App\Models\ProductionApi;
use App\Models\ProductionOutputTraceability;
use Illuminate\Support\Facades\Auth;
use App\Models\ProductionOutputTraceabilityDetail;

class OEEController extends Controller
{
    public function index()
    {
        if (Auth::user()->hasPermissionTo('Machine Status View')) {
            $machines = Machine::select('id', 'name', 'code', 'category')->get();
            return view('mes.oee.oee-report.index', compact('machines'));
        }
        return back()->with('custom_errors', 'You don`t have the right permission');
    }

    public function generate(Request $request)
    {
        $start_date = Carbon::now('Asia/Kuala_Lumpur')->startOfDay();
        $end_date = Carbon::now('Asia/Kuala_Lumpur')->endOfDay();
        $machines = Machine::select('id', 'name', 'code');

        if ($request->start_date) {
            $start_date = Carbon::parse($request->start_date)->format('d-m-Y h:i:s A');
            $end_date = Carbon::parse($request->end_date)->format('d-m-Y h:i:s A');
        }

        if ($request->machines) {
            $machines = $machines->whereIn('id', $request->machines);
        }

        $machines = $machines->get();

        $general_settings = SpecBreak::find(1);

        $oeeData = [];
        foreach ($machines as $machine) {
            $machine_api = MachineApi::where('mc_no', $machine->code)->whereNull('end_time')->orderBy('id', 'DESC')->first();

            $details = ProductionOutputTraceabilityDetail::join('production_output_traceabilities', 'production_output_traceability_details.pot_id', '=', 'production_output_traceabilities.id')
                ->selectRaw('
        SUM(DISTINCT production_output_traceabilities.planned_cycle_time) as planned_cycle_time,
        SUM(DISTINCT production_output_traceabilities.total_produced) as total_produced,
        SUM(DISTINCT production_output_traceabilities.total_rejected_qty) as total_rejected_qty
         ')
                ->where('production_output_traceability_details.machine_id', $machine->id)
                ->where('production_output_traceability_details.start_time', '>=', $start_date)
                ->where('production_output_traceability_details.end_time', '<=', $end_date)->first();
            $shift_lengths = ProductionOutputTraceabilityDetail::join('production_output_traceabilities', 'production_output_traceability_details.pot_id', '=', 'production_output_traceabilities.id')
                ->selectRaw('
                SUM(CASE WHEN production_output_traceabilities.spec_break = "Normal Hour" THEN ? * 60 ELSE 0 END) as normal_minutes,
                SUM(CASE WHEN production_output_traceabilities.spec_break = "OT" THEN ? * 60 ELSE 0 END) as ot_minutes,
                SUM(production_output_traceabilities.shift_length * 60) as shift_length_minutes
            ', [$general_settings->normal_hour, $general_settings->ot_hour])
                ->where('production_output_traceability_details.machine_id', $machine->id)
                ->where('production_output_traceability_details.start_time', '>=', $start_date)
                ->when(
                    $end_date,
                    function ($query) use ($end_date) {
                        $query->where('production_output_traceability_details.end_time', '<=', $end_date);
                    }
                )
                ->first();
            // dd($shift_lengths);

            $shift_length = $shift_lengths->shift_length_minutes;
            $breaks = $shift_lengths->normal_minutes + $shift_lengths->ot_minutes;

            $ideal_cycle_time = $details->planned_cycle_time;
            $total_pieces = $details->total_produced;
            $reject_pieces = $details->total_rejected_qty ?? 0;

            $planned_production_time = $shift_length - $breaks;

            $actual_running_time = MachineApi::select(DB::raw("
            SUM(TIMESTAMPDIFF(MINUTE,
                STR_TO_DATE(start_time, '%d-%m-%Y %H:%i:%s'),
                COALESCE(STR_TO_DATE(end_time, '%d-%m-%Y %H:%i:%s'), NOW())
            )) as actual_running_time
        "))->where('mc_no', $machine->code)->where('start_time', '>=', $start_date)->where(function ($query) use ($end_date) {
                $query->where('end_time', '<=', $end_date)
                    ->orWhereNull('end_time');
            })->first();

            $machine_preperation = MachinePreperation::select(DB::raw("
            SUM(TIMESTAMPDIFF(MINUTE,
                STR_TO_DATE(start_time, '%d-%m-%Y %H:%i:%s'),
                COALESCE(STR_TO_DATE(end_time, '%d-%m-%Y %H:%i:%s'), NOW())
            )) as machine_preperation
        "))->where('mc_no', $machine->code)->where('start_time', '>=', $start_date)->where(function ($query) use ($end_date) {
                $query->where('end_time', '<=', $end_date)
                    ->orWhereNull('end_time');
            })->first();

            $down_time = $planned_production_time - ($actual_running_time->actual_running_time ?? 0);
            $operation_time = $planned_production_time - $down_time - $machine_preperation->machine_preperation;
            $good_pieces = $total_pieces - $reject_pieces;

            $availability = ($planned_production_time != 0) ? ($operation_time / $planned_production_time) : 0;
            $performance = ($operation_time != 0) ? (($total_pieces * $ideal_cycle_time) / $operation_time) : 0;
            $quality = ($total_pieces != 0) ? ($good_pieces / $total_pieces) : 0;

            $oee = $availability * $performance * $quality;
            $production = ProductionOutputTraceabilityDetail::where('machine_id', $machine->id)->with('production.product')->orderBy('id', 'DESC')->first();

            $machine_production = ProductionApi::where('mc_no', $machine->code)->whereNull('end_time')->orderBy('id', 'DESC')->first();
            $preperation = MachinePreperation::where('mc_no', $machine->code)->whereNull('end_time')->orderBy('id', 'DESC')->first();
            $call_for_assistance = CallForAssistance::where('mc_no', $machine->code)->where('call', 1)->orderBy('id', 'DESC')->first();

            $oeeData[$machine->name] = [
                'availability' => round(max(0, min($availability, 100)), 2),
                'performance' => round(max(0, min($performance, 100)), 2),
                'quality' => round(max(0, min($quality, 100)), 2),
                'oee' => round(max(0, min($oee, 100)), 2),
                'status' => ($machine_api != null) ? 'ON' : 'OFF',
                'machine_production' => $machine_production,
                'preperation' => $preperation,
                'production' => $production,
                'call_for_assistance' => $call_for_assistance
            ];
        }

        return response()->json($oeeData);
    }

    public function details(Request $request)
    {
        $machine = Machine::where('name', $request->machine)->first();
        $start_date = Carbon::parse($request->start_date)->format('d-m-Y 00:00:00 A');
        $end_date = Carbon::parse($request->end_date)->setTime(23, 59, 59)->format('d-m-Y h:i:s A');

        $details = ProductionOutputTraceabilityDetail::where('machine_id', $machine->id)->where('start_time', '>=', $start_date)->where(function ($query) use ($end_date) {
            $query->where('end_time', '<=', $end_date)
                ->orWhereNull('end_time');
        })->get();

        $general_settings = SpecBreak::find(1);

        $data = [];
        // Calculate additional fields
        foreach ($details as $detail) {
            $traceability = ProductionOutputTraceability::with('product')->find($detail->pot_id);
            $shift_lengths = ProductionOutputTraceabilityDetail::join('production_output_traceabilities', 'production_output_traceability_details.pot_id', '=', 'production_output_traceabilities.id')
                ->selectRaw('
                SUM(CASE WHEN production_output_traceabilities.spec_break = "Normal Hour" THEN ? * 60 ELSE 0 END) as normal_minutes,
                SUM(CASE WHEN production_output_traceabilities.spec_break = "OT" THEN ? * 60 ELSE 0 END) as ot_minutes,
                SUM(production_output_traceabilities.shift_length * 60) as shift_length_minutes,
                MAX(production_output_traceability_details.end_time) as end_time
            ', [$general_settings->normal_hour, $general_settings->ot_hour])
                ->where('production_output_traceability_details.id', '>=', $detail->id)
                ->first();

            $shift_length = $shift_lengths->shift_length_minutes;
            $breaks = $shift_lengths->normal_minutes + $shift_lengths->ot_minutes;

            $planned_production_time = $shift_length - $breaks;

            if($detail->end_time == null){
                $detail->end_time = Carbon::now('Asia/Kuala_Lumpur')->format('d-m-Y h:i:s A');
            }

            $actual_running_time = MachineApi::where('start_time', '>=', $detail->start_time)
                ->where(function ($query) use ($detail) {
                    $query->where('end_time', '<=', $detail->end_time)
                        ->orWhereNull('end_time');
                })
                ->select(DB::raw("
                SUM(
                    TIMESTAMPDIFF(HOUR, 
                        STR_TO_DATE(start_time, '%d-%m-%Y %h:%i:%s %p'), 
                        COALESCE(STR_TO_DATE(end_time, '%d-%m-%Y %h:%i:%s %p'), NOW())
                    )
                ) AS actual_running_time
                "))
                ->first();

            $machine_preperation = MachinePreperation::select(DB::raw("SUM(TIMESTAMPDIFF(HOUR, 
                                STR_TO_DATE(start_time, '%d-%m-%Y %H:%i:%s'), 
                                COALESCE(STR_TO_DATE(end_time, '%d-%m-%Y %H:%i:%s'), NOW())
                            )) AS machine_preperation"))->where('start_time', '>=', $detail->start_time)->where(function ($query) use ($detail) {
                $query->where('end_time', '<=', $detail->end_time)
                    ->orWhereNull('end_time');
            })->first();

            $start_time = Carbon::parse($detail->start_time); // Convert start_time to Carbon
            $end_time = $detail->end_time ? Carbon::parse($detail->end_time) : Carbon::now('Asia/Kuala_Lumpur'); // Convert end_time to Carbon or use now()

            $production_time = $start_time->diffInHours($end_time);

            $down_time = $planned_production_time - ($actual_running_time->actual_running_time ?? 0);
            $operation_time = $planned_production_time - $down_time - $machine_preperation->machine_preperation;
            $good_pieces = $traceability->total_produced - $traceability->total_rejected_qty;

            $availability = ($planned_production_time != 0) ? ($operation_time / $planned_production_time) : 0;
            $performance = ($operation_time != 0) ? (($traceability->total_produced * $traceability->planned_cycle_time) / $operation_time) : 0;
            $quality = ($traceability->total_produced != 0) ? ($good_pieces / $traceability->total_produced) : 0;

            $oee = $availability * $performance * $quality;

            $data[] = [
                'order_no' => $traceability->po_no,
                'part_no' => $traceability->product->part_no ?? '',
                'cycle_time' => $traceability->actual_cycle_time ?? 0,
                'no_cavity' => $traceability->cavity ?? 0,
                'ideal_run_rate' => $traceability->planned_cycle_time ?? 0,
                'total_produced' => $traceability->total_produced ?? 0,
                'rejected_qty' => $traceability->total_rejected_qty ?? 0,
                'down_time' => $down_time,
                'production_time' => $production_time,
                'setup_time' => $machine_preperation->setup_time ?? 0,
                'availability' => round(max(0, min($availability, 100)), 2),
                'performance' => round(max(0, min($performance, 100)), 2),
                'quality' => round(max(0, min($quality, 100)), 2),
                'oee' => round(max(0, min($oee, 100)), 2)
            ];
        }

        return response()->json($data);
    }
}
