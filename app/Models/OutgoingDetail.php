<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OutgoingDetail extends Model
{
    use HasFactory, SoftDeletes;

    public function product(){
        return $this->belongsTo(Product::class,'product_id','id');
    }

    public function outgoing()
    {
        return $this->belongsTo(Outgoing::class, 'outgoing_id', 'id');
    }
}
