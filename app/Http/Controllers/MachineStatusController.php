<?php

namespace App\Http\Controllers;

use App\Models\Machine;
use App\Models\MachineApi;
use App\Models\MachineCount;
use App\Models\CallForAssistance;
use App\Models\MachinePreperation;
use App\Models\ProductionApi;
use Illuminate\Support\Facades\Auth;
use App\Models\ProductionOutputTraceabilityDetail;

class MachineStatusController extends Controller
{
    public function index()
    {
        if (Auth::user()->hasPermissionTo('Machine Status View')) {
            return view('mes.dashboard.machine-status');
        }
        return back()->with('custom_errors', 'You don`t have the right permission');
    }

    public function generate()
    {
        $machines = Machine::select('id', 'name', 'code')->get();
        $machines_array = [];
        foreach ($machines as $key => $machine) {
            $machine_api = MachineApi::where('mc_no', $machine->code)->whereNull('end_time')->orderBy('id', 'DESC')->first();
            $production = ProductionOutputTraceabilityDetail::where('machine_id', $machine->id)->with('production.product')->orderBy('id', 'DESC')->first();
            $count = 0;
            if ($production) {
                $count = MachineCount::where('mc_no', '=', $machine->code)->where('production_id', $production->pot_id)->sum('count');
            }
            $machine_production = ProductionApi::where('mc_no', $machine->code)->whereNull('end_time')->orderBy('id', 'DESC')->first();
            $machine_preperation = MachinePreperation::where('mc_no', $machine->code)->whereNull('end_time')->orderBy('id', 'DESC')->first();
            $call_for_assistance = CallForAssistance::where('mc_no', $machine->code)->where('call', 1)->orderBy('id', 'DESC')->first();
            $machines_array[] = [
                'name' => $machine->name,
                'code' => $machine->code,
                'count' => $count,
                'production' => $production,
                'machine_production' => $machine_production,
                'machine_preperation' => $machine_preperation,
                'machine_status' => ($machine_api) ? 'ON' : 'OFF',
                'call_for_assistance' => $call_for_assistance
            ];
        }
        return $machines_array;
    }
}
