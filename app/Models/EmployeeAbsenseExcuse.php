<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeAbsenseExcuse extends Model
{
    use HasFactory;
    protected $table = 'employee_absence_excuse';

    protected $fillable = [
        'employee_id',
        'excuse',
        'date',
        'authorized_person'
    ];


    public function employee(){
        return $this->hasOne(EmployeeList::class,'EmployeeID','employee_id');
    }


}
