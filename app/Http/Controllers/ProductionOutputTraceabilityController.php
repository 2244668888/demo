<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Process;
use App\Models\Machine;
use App\Models\MachineApi;
use Illuminate\Support\Str;
use App\Models\MachineCount;
use App\Models\MachineDownime;
use Illuminate\Http\Request;
use App\Models\ProductionApi;
use App\Models\TypeOfRejection;
use App\Models\MachinePreperation;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\ProductionOutputTraceability;
use App\Models\ProductionOutputTraceabilityQC;
use App\Models\ProductionOutputTraceabilityShift;
use App\Models\ProductionOutputTraceabilityDetail;
use App\Models\ProductionOutputTraceabilityRejection;

class ProductionOutputTraceabilityController extends Controller
{
    public function index()
    {
        if (
            Auth::user()->hasPermissionTo('Production Output Traceability List') ||
            Auth::user()->hasPermissionTo('Production Output Traceability Edit') ||
            Auth::user()->hasPermissionTo('Production Output Traceability QC') ||
            Auth::user()->hasPermissionTo('Production Output Traceability View')
        ) {
            $productions = ProductionOutputTraceability::with('product', 'machine.tonnage', 'daily_production')->get();

            // Get unique 'process'
            $uniqueProcesses = $productions->pluck('process')->unique();

            // Get unique 'po_no'
            $uniquePoNos = $productions->pluck('po_no')->unique();

            // Get unique 'part_no'
            $products = Product::select('id', 'part_no')->get();

            return view('mes.production.production-output-traceability.index', compact('productions', 'uniqueProcesses', 'uniquePoNos', 'products'));
        }
        return back()->with('custom_errors', 'You don`t have the right permission');
    }

    static public function store($traceability)
    {
        $traceabilities = new ProductionOutputTraceability();
        $traceabilities->dpp_id = $traceability['dpp_id'] ?? null;
        $traceabilities->planned_date = $traceability['planned_date'] ?? null;
        $traceabilities->planned_qty = $traceability['planned_qty'] ?? null;
        $traceabilities->operator = json_encode($traceability['op_name']) ?? null;
        $traceabilities->spec_break = $traceability['spec_break'] ?? null;
        $traceabilities->shift = ($traceability['shift'] == 'AM') ? 'DAY' : 'NIGHT';
        $traceabilities->machine_id = $traceability['machine'] ?? null;
        $traceabilities->cavity = $traceability['cavity'] ?? null;
        $traceabilities->po_no = $traceability['pro_order_no'] ?? null;
        $traceabilities->product_id = $traceability['product_id'] ?? null;
        $process = explode('/', $traceability['pro_order_no']);
        $traceabilities->process = $process[3] ?? null;
        $traceabilities->planned_cycle_time = $traceability['ct'];
        $traceabilities->total_produced = 0;
        $traceabilities->total_rejected_qty = 0;
        $traceabilities->total_good_qty = 0;
        $traceabilities->status = 'Not-initiated';
        $traceabilities->save();
    }

    public function edit($id)
    {
        $currentUrl = request()->url();
        if (Str::contains($currentUrl, 'production-output-traceability/edit/')) {
            if (!Auth::user()->hasPermissionTo('Production Output Traceability Edit')) {
                return back()->with('custom_errors', 'You don`t have the right permission');
            }
        } else if (Str::contains($currentUrl, 'production-output-traceability/qc/edit/')) {
            if (!Auth::user()->hasPermissionTo('Production Output Traceability QC')) {
                return back()->with('custom_errors', 'You don`t have the right permission');
            }
        } else {
            if (!Auth::user()->hasPermissionTo('Production Output Traceability View')) {
                return back()->with('custom_errors', 'You don`t have the right permission');
            }
        }
        $users = User::select('id', 'user_name')->get();
        $production = ProductionOutputTraceability::find($id);
        $machine = Machine::find($production->machine_id);
        $process = Process::where('name', $production->process)->first();
        $machines = Machine::select('id', 'name', 'code')->get();
        $type_of_rejections = TypeOfRejection::select('id', 'type')->get();
        $datetime = Carbon::now('Asia/Kuala_Lumpur')->format('d-m-Y h:i:s A') ?? null;
        $raw_materials = DailyProductionPlanningController::loadProcesses($production->product_id);
        if (isset($raw_materials['processes'])) {
            $raw_materials = $raw_materials['processes']->where('process_id', $process->id);
        }
        if ($production->shift == 'DAY') {
            $shift1 = 'AM';
            $shift2 = 'PM';
        } else if ($production->shift == 'NIGHT') {
            $shift1 = 'PM';
            $shift2 = 'AM';
        }
        $hourlyCounts = [];
        for ($i = 8; $i <= 19; $i++) {
            $hour = $i;
            $suffix = $shift1;
            if ($i > 11) {
                $hour = $i - 12;
                $suffix = $shift2;
            }
            $startTime = Carbon::parse($production->planned_date)->startOfDay()->hour($i);
            $endTime = $startTime->copy()->addHour();
            $startTime = $startTime->format('d-m-Y h:i:s A');
            $endTime = $endTime->format('d-m-Y h:i:s A');
            $nextHour = $hour + 1;
            $nextSuffix = $suffix;
            if ($nextHour == 12) {
                $nextSuffix = ($suffix == 'AM') ? 'PM' : 'AM';
            } elseif ($nextHour > 12) {
                $nextHour -= 12;
                $nextSuffix = $shift2;
            }
            $formattedTime = sprintf("%d%s-%d%s", $hour == 0 ? 12 : $hour, $suffix, $nextHour == 0 ? 12 : $nextHour, $nextSuffix);
            $machineCount = MachineCount::where('mc_no', '=', $machine->code)->whereBetween('datetime', [$startTime, $endTime])->where('production_id', $production->id)->sum('count');
            if ($machine->name == 'Assembly') {
                $machineCount = ProductionOutputTraceabilityShift::where('pot_id', $production->id)->where('time', $hour)->sum('good_qty');
            }
            $rejectCount = ProductionOutputTraceabilityShift::where('pot_id', $production->id)->where('time', $hour)->sum('reject_qty');
            $rejectRemarks = ProductionOutputTraceabilityShift::where('pot_id', $production->id)->where('time', $hour)->first();
            $hourlyCounts[$formattedTime] = [
                'machine_count' => $machineCount,
                'reject_count' => $rejectCount,
                'reject_remarks' => $rejectRemarks->remarks ?? ''
            ];
        }
        $shift_rejection = ProductionOutputTraceabilityRejection::where('pot_id', $production->id)->get();
        $count = MachineCount::where('mc_no', '=', $machine->code)->where('production_id', $production->id)->sum('count');
        $production_time = ProductionApi::where('mc_no', '=', $machine->code)->where('production_id', $production->id)->select(DB::raw('SUM(TIMESTAMPDIFF(SECOND, start_time, IFNULL(end_time, NOW()))) as total_duration'))->value('total_duration');
        $setup_time = MachinePreperation::where('mc_no', '=', $machine->code)->where('production_id', $production->id)->select(DB::raw('SUM(TIMESTAMPDIFF(SECOND, start_time, IFNULL(end_time, NOW()))) as total_duration'))->value('total_duration');
        $denominator = $production_time + $setup_time;
        $actual_time_hr = ($denominator > 0 && $count >= 0) ? $count / $denominator : 0;
        $check_machines = ProductionOutputTraceabilityDetail::where('machine_id', '=', $production->machine_id)->where('pot_id',  '=', $production->id)->orderby('id', 'DESC')->first();
        $details = ProductionOutputTraceabilityDetail::where('pot_id',  '=', $production->id)->with('machine')->orderby('id', 'ASC')->get();
        foreach ($details as $detail) {
            $end_time = $detail->end_time;
            if (!$end_time) {
                $end_time = Carbon::now('Asia/Kuala_Lumpur')->format('d-m-Y h:i:s A');
            }
            $detail->machine_count = MachineCount::where('mc_no', '=', $detail->machine->code)->where('production_id', $detail->pot_id)->where('datetime', '>=', $detail->start_time)->where('datetime', '<=', $end_time)->sum('count');
        }

        $production_details = ProductionApi::where('production_id', $id)
            ->get()
            ->map(function ($item) {
                $item->status = 'Production';
                $item->color = 'green';
                return $item;
            });

        $preperation_details = MachinePreperation::where('production_id', $id)
            ->get()
            ->map(function ($item) {
                $item->status = 'Production';
                $item->color = 'yellow';
                return $item;
            });

        $downtime_details = MachineDownime::where('production_id', $id)
            ->get()
            ->map(function ($item) {
                $item->status = 'Downtime';
                $item->color = 'red';
                return $item;
            });

        $sorted_details = collect([])
            ->merge($production_details)
            ->merge($preperation_details)
            ->merge($downtime_details);

        if (Str::contains($currentUrl, 'production-output-traceability/edit/')) {
            return view('mes.production.production-output-traceability.edit', compact('machines', 'users', 'type_of_rejections', 'production', 'count', 'raw_materials', 'hourlyCounts', 'shift_rejection', 'check_machines', 'details', 'production_time', 'setup_time', 'actual_time_hr', 'sorted_details'));
        } else if (Str::contains($currentUrl, 'production-output-traceability/qc/edit/')) {
            return view('mes.production.production-output-traceability.qc', compact('users', 'type_of_rejections', 'production', 'count', 'raw_materials', 'hourlyCounts', 'shift_rejection', 'details', 'production_time', 'setup_time', 'actual_time_hr', 'sorted_details'));
        } else {
            $qc_rejection = ProductionOutputTraceabilityQC::where('pot_id', $production->id)->get();
            return view('mes.production.production-output-traceability.view', compact('users', 'type_of_rejections', 'production', 'count', 'raw_materials', 'hourlyCounts', 'shift_rejection', 'qc_rejection', 'details', 'production_time', 'setup_time', 'actual_time_hr', 'sorted_details'));
        }
    }

    public function machine_count_data(Request $request){
        $machineCount = MachineCount::where('mc_no',$request->mc_no)->get();
        return ['machineCount'=>$machineCount];
    }

    public function update(Request $request, $id)
    {
        if (!Auth::user()->hasPermissionTo('Production Output Traceability Edit')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $production = ProductionOutputTraceability::find($id);
        $production->machine_id = $request->machine;
        $production->operator = json_encode($request->user);
        $production->leader_name = json_encode($request->leader);
        $production->shift_length = $request->shift_length;
        $production->purging_weight = $request->purging_weight;
        $production->report_qty = $request->report_qty;
        $production->remaining_qty = $request->remaining_qty;
        $production->planned_cycle_time = $request->planned_cycle_time;
        $production->actual_cycle_time = $request->actual_cycle_time;
        $production->planned_qty_hr = $request->planned_qty_hr;
        $production->actual_qty_hr = $request->actual_qty_hr;
        $production->total_produced = $request->total_total;
        $production->total_rejected_qty = $request->total_reject;
        $production->total_good_qty = $request->total_good;
        $production->save();

        $shiftData = [];
        for ($i = 1; $i <= 12; $i++) {
            $shiftData[] = [
                'pot_id' => $production->id,
                'dpp_id' => $production->dpp_id,
                'time' => $request->input("hour_$i"),
                'total_qty' => $request->input("total_$i"),
                'reject_qty' => $request->input("reject_$i"),
                'good_qty' => $request->input("good_$i"),
                'remarks' => $request->input("remarks_$i")
            ];
        }
        ProductionOutputTraceabilityShift::where('pot_id', $production->id)->delete();
        ProductionOutputTraceabilityShift::insert($shiftData);

        return redirect()->route('production_output_traceability.edit', $id)->with('custom_success', 'Production Output Traceability has been Successfully Update!');
    }

    public function rejection(Request $request)
    {
        ProductionOutputTraceabilityRejection::where('pot_id', $request->id)->delete();
        foreach ($request->rejection_data as $value) {
            $detail = new ProductionOutputTraceabilityRejection();
            $detail->pot_id = $request->id;
            $detail->time = $value['time'] ?? null;
            $detail->rt_id = $value['rejection'] ?? null;
            $detail->comments = $value['comments'] ?? null;
            $detail->qty = $value['qty'] ?? 0;
            $detail->save();
        }

        return response()->json('Successfully');
    }

    public function qc_update(Request $request, $id)
    {
        if (!Auth::user()->hasPermissionTo('Production Output Traceability QC')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }
        $production = ProductionOutputTraceability::find($id);
        $production->qc_produced = $request->qc_total_total;
        $production->qc_rejected_qty = $request->qc_total_reject;
        $production->qc_good_qty = $request->qc_total_good;
        $production->status = 'Checked';
        $production->save();

        $storedData = json_decode($request->input('details'), true);

        $newArray = collect($storedData)->flatMap(function ($subArray) {
            return $subArray;
        })->sortBy('time')->values()->toArray();

        foreach ($newArray as $key => $value) {
            $detail = new ProductionOutputTraceabilityQC();
            $detail->pot_id = $production->id;
            $detail->rt_id = $value['rejection'] ?? null;
            $detail->comments = $value['comments'] ?? null;
            $detail->qty = $value['qty'] ?? 0;
            $detail->save();
        }

        return redirect()->route('production_output_traceability.index')->with('custom_success', 'Production Output Traceability has been Successfully Checked!');
    }

    public function starter(Request $request)
    {
        $ismachinestart = ProductionOutputTraceabilityDetail::where('end_time', '=', null)->where('machine_id', '=', $request->machine)->where('pot_id', '!=', $request->production_id)->orderby('id', 'DESC')->first();

        $alreadyexist = ProductionOutputTraceabilityDetail::where('status', '=', 1)->where('machine_id', '=', $request->machine)->where('pot_id', '=', $request->production_id)->orderby('id', 'DESC')->first();
        $stopped = ProductionOutputTraceabilityDetail::where('machine_id', '=', $request->machine)->where('pot_id', '=', $request->production_id)->where('status', '=', 3)->first();

        if (!$ismachinestart) {
            if ($request->status == 1 && !$alreadyexist && !$stopped) {
                $machine = Machine::find($request->machine);
                if ($machine->name != 'Assembly') {
                    $running = MachineApi::where('mc_no', '=', $machine->code)->whereNull('end_time')->orderBy('id', 'DESC')->first();
                    if (!$running) {
                        $check_machine = ProductionOutputTraceabilityDetail::where('machine_id', '=', $request->machine)->where('pot_id',  '=', $request->production_id)->orderby('id', 'DESC')->first();
                        $details = ProductionOutputTraceabilityDetail::where('pot_id',  '=', $request->production_id)->with('machine')->orderby('id', 'ASC')->get();
                        foreach ($details as $detail) {
                            $detail->machine_count = MachineCount::where('mc_no', '=', $detail->machine->code)->whereBetween('datetime', [$detail->start_time, $detail->end_time])->where('production_id', $detail->pot_id)->count();
                        }
                        return response()->json([
                            'message' => 'Can`t Start, Machine Not Running on Site!',
                            'check_machine' => $check_machine,
                            'details' => $details
                        ]);
                    }
                }
                ProductionOutputTraceabilityDetail::create([
                    'machine_id' => $request->machine,
                    'pot_id' => $request->production_id,
                    'status' => $request->status,
                    'operator' => json_encode($request->operator),
                    'start_time' => Carbon::now('Asia/Kuala_Lumpur')->format('d-m-Y h:i:s A')
                ]);
                $digital = ProductionOutputTraceability::find($request->production_id);
                $digital->status = 'Start';
                $digital->save();
                $check_machine = ProductionOutputTraceabilityDetail::where('machine_id', '=', $request->machine)->where('pot_id',  '=', $request->production_id)->orderby('id', 'DESC')->first();
                $details = ProductionOutputTraceabilityDetail::where('pot_id',  '=', $request->production_id)->with('machine')->orderby('id', 'ASC')->get();
                foreach ($details as $detail) {
                    $detail->machine_count = MachineCount::where('mc_no', '=', $detail->machine->code)->whereBetween('datetime', [$detail->start_time, $detail->end_time])->where('production_id', $detail->pot_id)->count();
                }
                return response()->json([
                    'message' => 'Machine Started ' . Carbon::now('Asia/Kuala_Lumpur')->format('d-m-Y h:i:s A'),
                    'check_machine' => $check_machine,
                    'details' => $details
                ]);
            } else if ($request->status == 2 && !$stopped) {
                $mpo = ProductionOutputTraceabilityDetail::where('machine_id', $request->machine)->where('pot_id', $request->production_id)->where('end_time', '=', null)->orderby('id', 'DESC')->first();
                $mpo->status = $request->status;
                $mpo->end_time = Carbon::now('Asia/Kuala_Lumpur')->format('d-m-Y h:i:s A');
                $mpo->pause_remarks = $request->remarks;
                $mpo->save();
                $start_time = Carbon::parse($mpo->start_time);
                $end_time = Carbon::parse($mpo->end_time);
                $duration = $end_time->diffInMinutes($start_time);
                $mpo->duration = $duration;
                $mpo->save();
                $digital = ProductionOutputTraceability::find($request->production_id);
                $digital->status = 'Pause';
                $digital->save();
                $check_machine = ProductionOutputTraceabilityDetail::where('machine_id', '=', $request->machine)->where('pot_id',  '=', $request->production_id)->orderby('id', 'DESC')->first();
                $details = ProductionOutputTraceabilityDetail::where('pot_id',  '=', $request->production_id)->with('machine')->orderby('id', 'ASC')->get();
                foreach ($details as $detail) {
                    $detail->machine_count = MachineCount::where('mc_no', '=', $detail->machine->code)->whereBetween('datetime', [$detail->start_time, $detail->end_time])->where('production_id', $detail->pot_id)->count();
                }
                return response()->json([
                    'message' => 'Machine Paused ' . Carbon::now('Asia/Kuala_Lumpur')->format('d-m-Y h:i:s A'),
                    'check_machine' => $check_machine,
                    'details' => $details
                ]);
            } else if ($request->status == 3 && !$stopped) {
                $mpo = ProductionOutputTraceabilityDetail::where('machine_id', $request->machine)->where('pot_id', $request->production_id)->orderby('id', 'DESC')->first();
                $mpo->status = $request->status;
                $mpo->end_time = Carbon::now('Asia/Kuala_Lumpur')->format('d-m-Y h:i:s A');
                $mpo->remarks = $request->remarks;
                $mpo->save();
                $start_time = Carbon::parse($mpo->start_time);
                $end_time = Carbon::parse($mpo->end_time);
                $duration = $end_time->diffInMinutes($start_time);
                $mpo->duration = $duration;
                $mpo->save();
                $digital = ProductionOutputTraceability::find($request->production_id);
                $digital->status = 'Stop';
                $digital->save();
                $check_machine = ProductionOutputTraceabilityDetail::where('machine_id', '=', $request->machine)->where('pot_id',  '=', $request->production_id)->orderby('id', 'DESC')->first();
                $details = ProductionOutputTraceabilityDetail::where('pot_id',  '=', $request->production_id)->with('machine')->orderby('id', 'ASC')->get();
                foreach ($details as $detail) {
                    $detail->machine_count = MachineCount::where('mc_no', '=', $detail->machine->code)->whereBetween('datetime', [$detail->start_time, $detail->end_time])->where('production_id', $detail->pot_id)->count();
                }
                return response()->json([
                    'message' => 'Machine Stopped ' . Carbon::now('Asia/Kuala_Lumpur')->format('d-m-Y h:i:s A'),
                    'check_machine' => $check_machine,
                    'details' => $details
                ]);
            }
        } else {
            $check_machine = ProductionOutputTraceabilityDetail::where('machine_id', '=', $request->machine)->where('pot_id',  '=', $request->production_id)->orderby('id', 'DESC')->first();
            $details = ProductionOutputTraceabilityDetail::where('pot_id',  '=', $request->production_id)->with('machine')->orderby('id', 'ASC')->get();
            foreach ($details as $detail) {
                $detail->machine_count = MachineCount::where('mc_no', '=', $detail->machine->code)->whereBetween('datetime', [$detail->start_time, $detail->end_time])->where('production_id', $detail->pot_id)->count();
            }
            return response()->json([
                'message' => 'Same Machine Is Running In Other Production!',
                'check_machine' => $check_machine,
                'details' => $details
            ]);
        }
    }
}
