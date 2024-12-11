<?php

namespace App\Http\Controllers;

use App\Models\ProductionOutputTraceability;
use App\Models\ProductionOutputTraceabilityDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductionSchedulingController extends Controller
{
    public function index(){
        if (!Auth::user()->hasPermissionTo('Production Scheduling View')) {
            return back()->with('custom_errors', 'You don`t have the right permission');
        }

        $production_schedules = ProductionOutputTraceability::select('planned_date')->distinct()->get();

        return view('mes.ppc.production_scheduling',compact('production_schedules'));
    }

    public function getSchedules(Request $request)
    {
        $production_schedules = ProductionOutputTraceability::with('machine')->where('planned_date', $request->date)->get();

        return response()->json($production_schedules);
    }
}
