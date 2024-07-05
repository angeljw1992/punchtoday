<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'status',
        'date',
        'entrance',
        'lunch_departure',
        'lunch_entry',
        'exit',
        'hours_worked'
    ];

}
