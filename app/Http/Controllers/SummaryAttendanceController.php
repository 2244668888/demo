<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Department;
use App\Models\leave;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SummaryAttendanceController extends Controller
{
    public function index()
    {
        if (Auth::user()->hasPermissionTo('Summary Attendence List')) {
            $staff = User::select('id', 'user_name', 'code')->get();
            $department = Department::select('id', 'name')->get();
            return view('hr.summary_attendance.index',
                compact('staff', 'department')
            );
        }
        return back()->with(
            'custom_errors',
            'You don`t have the right permission'
        );
    }

    public function report(Request $request)
    {

        $staff_id = $request->staff_id;
        $depart_id = $request->depart_id;


        $default_start_date = ($request->start_date != '' || $request->start_date != null) ? $request->start_date : Carbon::now()->subYear()->toDateString();
        $default_end_date = ($request->end_date != '' || $request->end_date != null) ? $request->end_date : Carbon::now()->toDateString();
        // dd($default_start_date,$default_end_date);
        $leaveDataQuery = leave::select(
            'leaves.name',
            'users.user_name',
            'users.code',
            'departments.name as department_name',
            DB::raw('SUM(DATEDIFF(to_date, from_date)) as total_leave_days'),
            DB::raw(
                'SUM(CASE WHEN entitlement = "Medical" THEN DATEDIFF(to_date, from_date) ELSE 0 END) as medical_leave_days'
            )
        )
            ->join('users', 'leaves.name', '=', 'users.user_name')
            ->join('departments', 'users.department_id', '=', 'departments.id')
            ->where('leaves.from_date', '>=', $default_start_date)
            ->where('leaves.to_date', '<=', $default_end_date);

        if ($staff_id) {
            $leaveDataQuery->where('users.id', $staff_id);
        }

        if ($depart_id) {
            $leaveDataQuery->where('departments.id', $depart_id);
        }

        // Execute leaveData query
        $leaveData = $leaveDataQuery->groupBy(
            'leaves.name',
            'users.user_name',
            'users.code',
            'departments.name'
        )
            ->get();
            $start_date = Carbon::createFromFormat('Y-m-d', $default_start_date)->format('Y-m-d');
            $end_date = Carbon::createFromFormat('Y-m-d', $default_end_date)->format('Y-m-d');
            // dd($start_date,$end_date);

        $attendanceDataQuery = Attendance::select(
            'no',
            'name',
            DB::raw('COUNT(CASE WHEN absent != 0 THEN 1 END) as total_absent'),
            DB::raw('COUNT(CASE WHEN absent != 1 THEN 1 END) as attend_days'),
            DB::raw('SUM(ot_time) as total_ot_hours')
        )

            ->whereDate('date', '>=', $start_date)
            ->whereDate('date', '<=', $end_date);

        if ($staff_id) {
            $attendanceDataQuery->where('no', $staff_id);
        }

        $attendanceData = $attendanceDataQuery->groupBy('no', 'name')
            ->get();
        // dd($attendanceData);

        // Prepare the data to return as a response
        $finalData = [];

        foreach ($attendanceData as $attendances) {
            // Find matching leave data for the user
            $leaveForUser = $leaveData->firstWhere('code', $attendances->no);
            // dd($leaveForUser);
            // Default leave values if no matching leave data found
            $total_leave_days = $leaveForUser->total_leave_days ?? 0;
            $medical_leave_days = $leaveForUser->medical_leave_days ?? 0;

            // Add to final data
            $finalData[] = [
                'emp_id' => $attendances->no,
                'emp_name' => $attendances->name,
                'attend_days' => $attendances->attend_days, // Assuming you want to calculate or set this separately
                'total_leave' => $total_leave_days,
                'total_medical_leave' => $medical_leave_days,
                'absent_days' => $attendances->total_absent,
                'ot_time' => $attendances->total_ot_hours
            ];
        }
        // dd($start_date,$end_date);

        // Return the final response as JSON
        return response()->json($finalData);
    }
}
