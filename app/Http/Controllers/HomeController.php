<?php

namespace App\Http\Controllers;

use App\Models\CallForAssistance;
use App\Models\Machine;
use App\Models\MachineApi;
use App\Models\MachinePreperation;
use App\Models\ProductionApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (Auth::user()->hasPermissionTo('Shopfloor View')){
            $machines = Machine::select('id', 'name', 'code')->whereIn('name', ['M1', 'M2', 'M3', 'M4', 'M5', 'M6', 'M7', 'M8', 'M9', 'M10', 'M11', 'M12', 'M13', 'M14'])->get();
            $machines_array = [];
            foreach ($machines as $key => $machine) {
                $color = 'gray';
                $machine_api = MachineApi::where('mc_no', $machine->code)->orderBy('id', 'DESC')->first();
                $production_api = ProductionApi::where('mc_no', $machine->code)->orderBy('id', 'DESC')->first();
                $machine_preperation = MachinePreperation::where('mc_no', $machine->code)->orderBy('id', 'DESC')->first();
                $call_for_assistance = CallForAssistance::where('mc_no', $machine->code)->where('call', 1)->orderBy('id', 'DESC')->first();
                if ($machine_api) {
                    if ($machine_api->end_time == null) {
                        $color = 'success';
                    }
                    if ($production_api) {
                        if ($machine_api->end_time != null && $production_api->end_time == null) {
                            $color = 'danger';
                        }
                    }
                }
                if ($machine_preperation) {
                    if ($machine_preperation->end_time == null) {
                        $color = 'warning';
                    }
                }
                $machines_array[] = [
                    'color' => $color,
                    'name' => $machine->name,
                    'code' => $machine->code,
                    'call_for_assistance' => $call_for_assistance
                ];
            }
            return view('mes.dashboard.shopfloor', compact('machines_array'));
        }
    }
}
