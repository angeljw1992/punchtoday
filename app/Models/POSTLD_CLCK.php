<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class POSTLD_CLCK extends Model
{
    use HasFactory;
    protected $table = 'POSTLD_CLCK';

    protected $guarded = [];

    public $timestamps = false;

    public function employee(){
        return $this->hasOne(EmployeeList::class,'EmployeeID','EmployeeID');
    }

    public function POSTLD_CLCK()
    {
        return $this->hasOne(PostLdClckDetails::class, "ROWID", "ROWID");
    }

}
