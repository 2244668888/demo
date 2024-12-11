<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseReturn extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'qty'
    ];

    public function purchase_order(){
        return $this->belongsTo(PurchaseOrder::class,'po_id','id');
    }

    public function user(){
        return $this->belongsTo(User::class,'created_by','id');
    }

    public function checked_bye(){
        return $this->belongsTo(User::class,'checked_by','id');
    }

    public function receive_bye(){
        return $this->belongsTo(User::class,'receive_by','id');
    }

    public function supplier(){
        return $this->belongsTo(Supplier::class,'supplier_id','id');
    }

    public function purchase_return_detail(){
        return $this->hasMany(PurchaseReturnProduct::class,'purchase_return_id','id');
    }
}
