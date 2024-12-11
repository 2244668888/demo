<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PrApproval extends Model
{
    use HasFactory, SoftDeletes;
    public function designation()
    {
         return $this->belongsTo(Designation::class, 'designation_id', 'id');
    }
    
    public function department()
    {
         return $this->belongsTo(Department::class, 'department_id', 'id');
    }
 
}
