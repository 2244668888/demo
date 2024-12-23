<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalePrice extends Model
{
    use HasFactory, SoftDeletes;

    public function product(){
        return $this->belongsTo(Product::class,'product_id','id');
    }
    // public function products(){
    //     return $this->belongsTo(Product::class,'product_id','id');
    // }
}
