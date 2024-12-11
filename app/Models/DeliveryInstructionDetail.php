<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeliveryInstructionDetail extends Model
{
    use HasFactory, softDeletes;

    public function products(){
        return $this->belongsTo(Product::class,'product_id','id');
    }
}
