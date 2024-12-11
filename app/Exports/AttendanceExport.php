<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Http\Request;
class AttendanceExport implements FromCollection, WithHeadings
{
    use Exportable;
    protected $attendance;
    public function __construct($attendance)
    {

         $this->attendance = $attendance;
    }
     public function collection()
        {
         return $this->attendance;
     }
    /**
     * Specify the headings for the exported CSV file.
     *
     * @return array
     */
    public function headings(): array
    {
        return [

            'no',
            'name',
            'auto_assign',
            'date',
            'timetable',
            'on_duty',
            'off_duty',
            'clock_in',
            'clock_out',
            'normal',
            'real_time',
            'late',
            'early',
            'ot_time',
            'work_time',
            'exception',
            'department',
            'n_days',
            'week_end',
            'holiday',
            'att_time',
            'n_days_ot',
            'weekend_ot',
            'holiday_ot'
        ];
    }
}
