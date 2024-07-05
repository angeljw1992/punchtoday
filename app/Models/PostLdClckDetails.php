<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostLdClckDetails extends Model
{
    use HasFactory;
    protected $table = 'postld_clck_details';

    protected $fillable = ['ROWID','Punch_TimeStamp','Modified_TimeStamp','AutorizadoPor','BusinessDate','EmployeeID','Punch_Type'];

    public function POSTLD_CLCK()
    {
        return $this->belongsTo(POSTLD_CLCK::class,'ROWID','ROWID');
    }

}
