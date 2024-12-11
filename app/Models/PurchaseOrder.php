<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseOrder extends Model
{
    use HasFactory, SoftDeletes;

    public function purchase_requisition(){
        return $this->belongsTo(PurchaseRequisition::class,'pr_id','id');
    }

    public function purchase_planning(){
        return $this->belongsTo(PurchasePlanning::class,'pp_id','id');
    }

    public function user(){
        return $this->belongsTo(User::class,'created_by','id');
    }

    public function checked_bye(){
        return $this->belongsTo(User::class,'checked_by','id');
    }

    public function verify_bye(){
        return $this->belongsTo(User::class,'verify_by','id');
    }

    public function supplier(){
        return $this->belongsTo(Supplier::class,'supplier_id','id');
    }

    public function department(){
        return $this->belongsTo(Department::class,'department_id','id');
    }

    public function purchase_order_detail(){
        return $this->hasMany(PurchaseOrderDetail::class,'purchase_order_id','id');
    }
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

}
