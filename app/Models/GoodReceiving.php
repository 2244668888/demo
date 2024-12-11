<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GoodReceiving extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'incoming_qty',
        'received_qty'
    ];

    public function purchase_return(){
        return $this->belongsTo(PurchaseReturn::class,'pr_id','id');
    }

    public function purchase_order(){
        return $this->belongsTo(PurchaseOrder::class,'po_id','id');
    }

    public function user(){
        return $this->belongsTo(User::class,'created_by','id');
    }

}
