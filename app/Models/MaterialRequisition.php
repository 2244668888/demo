<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MaterialRequisition extends Model
{
    use HasFactory,SoftDeletes;
    public function machines(){
        return $this->belongsTo(Machine::class, 'machine', 'id');
    }

    public function department_from(){
        return $this->belongsTo(Department::class, 'request_from', 'id');
    }

    public function department_to(){
        return $this->belongsTo(Department::class, 'request_to', 'id');
    }
}
