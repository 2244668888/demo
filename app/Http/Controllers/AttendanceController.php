<?php

namespace App\Http\Controllers;


use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\AttendanceImport;
use App\Exports\AttendanceExport;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class AttendanceController extends Controller
{
    public function Data(Request $request)
    {

        if ($request->ajax()) {

            $query = Attendance::select(
                'attendances.id',
                'attendances.no',
                'attendances.name',
                'attendances.department',
                'attendances.date',
                'attendances.late',
                'attendances.clock_in',
                'attendances.clock_out',
                'attendances.work_time',
                'attendances.ot_time',
                'attendances.remarks',
            );

// // dd($request->all());

            $datatable = DataTables::eloquent($query)
            ->addIndexColumn()
            ->addColumn('remarks', function($row){
                $btn = '<div class="d-flex">';
                $role_ids = json_decode(Auth::user()->role_ids); // Ensure it's plural because it's an array
                if (is_null($row->remarks) && (is_array($role_ids) && in_array(1, $role_ids))) {
                    $btn .= '<input type="hidden" class="remarks" value="'.$row->remarks.'"><button type="button" class="btn btn-danger btn-sm add-remarks" data-id="' . $row->id . '" data-remark="'.$row->remarks.'">Add</button>';
                } elseif(!is_null($row->remarks) && in_array(needle: 1, haystack: $role_ids)) {
                    $btn .= '<input type="hidden" class="remarks" value="'.$row->remarks.'"><button type="button" class="btn btn-info btn-sm edit-remarks" data-id="' . $row->id . '" data-remark="'.$row->remarks.'">Edit</button>';
                }else{
                    $btn .= '<input type="hidden" class="remarks" value="'.$row->remarks.'"><button type="button" class="btn btn-info btn-sm edit-view" data-id="' . $row->id . '" data-remark="'.$row->remarks.'">View</button>';
                }
                $btn .= '</div>';
                return $btn;
            })
            ->rawColumns(['remarks']);
            // dd($request->search['value']);
                // dd($request->search['value']);




                if($request->search['value'] == null ){

                    $datatable = $datatable->filter(function ($query) use ($request) {
                    if ($request->has(key: 'code') && !is_null($request->code)) {
                        $query->where('no', 'like', "%{$request->code}%");
                    }
                    if ($request->has('name') && !is_null($request->name)) {
                        $query->where('name', 'like', "%{$request->name}%");
                    }
                    if ($request->has('department') && !is_null($request->department)) {
                        $query->where('department', 'like', "%{$request->department}%");
                    }
                    if ($request->has('late') && !is_null($request->department)) {
                        $query->where('late', 'like', "%{$request->department}%");
                    }
                    if ($request->has('date') && !is_null($request->date)) {
                        $query->where('date', 'like', "%{$request->date}%");
                    }
                    if ($request->has('clock_in') && !is_null($request->clock_in)) {
                        $query->where('clock_in', 'like', "%{$request->clock_in}%");
                    }
                    if ($request->has('clock_out') && !is_null($request->clock_out)) {
                        $query->where('clock_out', 'like', "%{$request->clock_out}%");
                    }
                    if ($request->has('work_time') && !is_null($request->work_time)) {
                        $query->where('work_time', 'like', "%{$request->work_time}%");
                    }
                    if ($request->has('ot_time') && !is_null($request->ot_time)) {
                        $query->where('ot_time', 'like', "%{$request->ot_time}%");
                    }
                    // if ($request->has('remarks') && !is_null($request->remarks)) {
                    //     $query->where('remarks', 'like', "%{$request->remarks}%");
                    // }
                    // if ($request->has('status') && !is_null($request->status)) {
                    //     $query->where('status', 'like', "%{$request->status}%");
                    // }

                });
            }

               return $datatable->make(true);
        }




    }
    public function index(){
    //   if (
    //     // Auth::user()->hasPermissionTo('Attendace List') ||
    //     // Auth::user()->hasPermissionTo('Attendace Import') ||
    //     // Auth::user()->hasPermissionTo('Attendace Export')

    //     ){
            // dd($leave);
            return view('hr.attendance.index');
        // }
        // return back()->with('custom_errors', 'You don`t have the right permission');
    }
    public function attendanceExcelImport(Request $request){
        // dd($request);
            $validated = $request->validate([
                'import' => 'file|mimes:xlsx,xls,csv',
            ]);

            // dd($request->hasFile('import'));
            if ($request->hasFile('import')) {
                try {
                    $file = $request->file('import');
                    // dd($file);
                    // Import the file
                    $import = new AttendanceImport();
                    Excel::import($import, $file);

                    return back()->with('success', 'File imported successfully!');
                } catch (\Exception $e) {
                    Log::error('File import error: ' . $e->getMessage());
                    return back()->with('custom_errors', 'There was an error importing the file: ' . $e->getMessage());
                }
            } else {
                return back()->with('custom_errors', 'The file is not uploaded!');
            }
    }

    public function attendanceExcelExport(Request $request){
        // dd($request);


            // dd($request->hasFile('import'));
        $attendance = Attendance::select('*')->get();
        // dd($attendanceData);
        $attendanceData = $attendance->map(function ($attendance) {
            return [
                'no' => $attendance->no,
                'name' => $attendance->name,
                'auto_assign' => $attendance->auto_assign,
                'date' => $attendance->date,
                'timetable' => $attendance->timetable,
                'on_duty' => $attendance->on_duty,
                'off_duty' => $attendance->off_duty,
                'clock_in' => $attendance->clock_in,
                'clock_out' => $attendance->clock_out,
                'normal' => $attendance->normal,
                'real_time' => $attendance->real_time,
                'late' => $attendance->late,
                'early' => $attendance->early,
                'ot_time' => $attendance->ot_time,
                'work_time' => $attendance->work_time,
                'exception' => $attendance->exception,
                'department' => $attendance->department,
                'ndays' => $attendance->n_days,
                'weekend' => $attendance->week_end,
                'holiday' => $attendance->holiday,
                'absent' => $attendance->absent,
                'att_time' => $attendance->att_time,
                'ndays_ot' => $attendance->n_days_ot,
                'weekend_ot' => $attendance->weekend_ot,
                'holiday_ot' => $attendance->holiday_ot
            ];
        });
        // dd($attendanceData);
        return Excel::download(new AttendanceExport($attendanceData), 'AttendanceExport.xls');


    }

    public function saveRemark(Request $request)
    {
        $request->validate([
            'row_id' => 'required|integer',
            'remarks' => 'required|string|max:255',
        ]);

        $row = Attendance::findOrFail($request->row_id);
        $row->remarks = $request->remarks;
        $row->save();

        return redirect()->back()->with('success', 'Remark saved successfully.');
    }


}
