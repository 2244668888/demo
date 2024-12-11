<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchasePlanningSupplier extends Model
{
    use HasFactory, SoftDeletes;

    public function product(){
        return $this->belongsTo(Product::class,'product_id','id');
    }

     public function supplier(){
        return $this->belongsTo(Supplier::class,'supplier_id','id');
    }

}
