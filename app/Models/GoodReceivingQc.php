<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GoodReceivingQc extends Model
{
    use HasFactory, SoftDeletes;

    public function type_of_rejection()
    {
        return $this->belongsTo(TypeOfRejection::class, 'rt_id', 'id');
    }

    public function product(){
        return $this->belongsTo(Product::class,'product_id','id');
    }

    public function good_receiving()
    {
        return $this->belongsTo(GoodReceiving::class, 'gr_id', 'id');
    }
}