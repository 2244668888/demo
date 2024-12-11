<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Outgoing extends Model
{
    use HasFactory, SoftDeletes;

    public function order(){
        return $this->belongsTo(Order::class,'order_id','id');
    }

    public function sales_return(){
        return $this->belongsTo(SalesReturn::class,'sr_id','id');
    }

    public function purchase_return(){
        return $this->belongsTo(PurchaseReturn::class,'pr_id','id');
    }

    public function user(){
        return $this->belongsTo(User::class,'created_by','id');
    }

    public function outgoing_detail(){
        return $this->hasMany(OutgoingDetail::class,'outgoing_id','id');
    }
}
