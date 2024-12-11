<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchasePlanning extends Model
{
    use HasFactory, SoftDeletes;

    public function order(){
        return $this->belongsTo(Order::class,'order_id','id');
    }

    public function user(){
        return $this->belongsTo(User::class,'created_by','id');
    }

    public function planning_detail(){
        return $this->hasMany(PurchasePlanningDetail::class,'purchase_planning_id','id');
    }
    public function verification(){
        return $this->hasMany(PurchasePlanningVerification::class,'purchase_planning_id','id');
    }

    public function latestVerification()
    {
        return $this->hasOne(PurchasePlanningVerification::class, 'purchase_planning_id')->latest()->with('department');
    }

    public function verificationOne(){
        return $this->hasMany(PurchasePlanningVerification::class,'purchase_planning_id','id');
    }

    public function purchase_planning_suppliers(){
        return $this->hasMany(PurchasePlanningSupplier::class,'purchase_planning_id','id');
    }


//     public function verificationOne()
// {
//     return $this->hasOne(PurchasePlanningVerification::class)->latestOfMany();
// }
}
