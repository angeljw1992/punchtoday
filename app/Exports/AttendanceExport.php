<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
class AttendanceExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */

    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }
    public function collection()
    {
        return collect($this->data);
    }

    public function headings(): array
    {
        return [
            'Employee Name',
            'Business Date',
            'Entrance Time',
            'Exit Time',
            'Lunch Departure Time',
            'Lunch Entry Time',
            'Hours Worked'
        ];
    }

    public function map($row): array
    {
        return [
            $row['employee_name'],
            $row['BusinessDate'],
            $row['entrance'],
            $row['exit'],
            $row['lunch_departure'],
            $row['lunch_entry'],
            $row['hours_worked']
        ];
    }
}
