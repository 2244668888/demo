<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchasePlanningVerification extends Model
{
    use HasFactory, SoftDeletes;

    public function department(){
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    public function designation(){
        return $this->belongsTo(Designation::class, 'designation_id', 'id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
