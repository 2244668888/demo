<?php
namespace App\Imports;

use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
class AttendanceImport implements ToModel, WithHeadingRow, SkipsEmptyRows
{
    public function __construct()
    {
    }
    public function model(array $row)
    {
       $attendanceImport = new Attendance();
       $attendanceImport->no = $row['no'];
       $attendanceImport->name = $row['name'];
       $attendanceImport->auto_assign = $row['auto_assign'];
       $attendanceImport->date = $this->transformDate($row['date']);
       $attendanceImport->timetable = $row['timetable'];
       $attendanceImport->on_duty = $row['on_duty'];
       $attendanceImport->off_duty = $row['off_duty'];
       $attendanceImport->clock_in = $this->transformTime($row['clock_in']);
       $attendanceImport->clock_out = $this->transformTime($row['clock_out']);
       $attendanceImport->normal = $row['normal'];
       $attendanceImport->real_time = $row['real_time'];
       $attendanceImport->late = $row['late'];
       $attendanceImport->early = $row['early'];
       $attendanceImport->ot_time = $row['ot_time'];
       $attendanceImport->work_time = $row['work_time'];
       $attendanceImport->exception = $row['exception'];
       $attendanceImport->department = $row['department'];
       $attendanceImport->n_days = $row['ndays'];
       $attendanceImport->week_end = $row['weekend'];
       $attendanceImport->holiday = $row['holiday'];
       $attendanceImport->absent = $row['absent'];
       $attendanceImport->att_time = $row['att_time'];
       $attendanceImport->n_days_ot = $row['ndays_ot'];
       $attendanceImport->weekend_ot = $row['weekend_ot'];
       $attendanceImport->holiday_ot = $row['holiday_ot'];
       $attendanceImport->save();
    }

    private function transformDate($excelDate)
    {
        if (is_numeric($excelDate)) {
            $unixDate = ($excelDate - 25569) * 86400;
            return gmdate("d/m/y", $unixDate);
        }
        return $excelDate;
    }

    private function transformTime($excelTime)
    {
        if (is_numeric($excelTime)) {
            $seconds = $excelTime * 86400;
            return gmdate("H:i", $seconds); 
        }
        return $excelTime;
    }
    public function headingRow(): int
    {
        return 1; // Assuming the heading row is on the second row
    }
    public function startRow(): int
    {
        return 2; // Assuming the data starts from the third row
    }
}

