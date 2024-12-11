<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseRequisition extends Model
{
    use HasFactory,SoftDeletes;

    public function department(){
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }
    public function user(){
        return $this->belongsTo(User::class, 'requested_by', 'id');
    }

    public function requisition_detail(){
        return $this->hasMany(PurchaseRequisitionDetail::class,'purchase_requisition_id','id');
    }

    public function verified_by_relation(){
        return $this->belongsTo(User::class, 'verified_by_id', 'id');
    }




}
