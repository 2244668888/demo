<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseReceiveLocation extends Model
{
    use HasFactory, SoftDeletes;

    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id', 'id');
    }

    public function rack()
    {
        return $this->belongsTo(AreaRack::class, 'rack_id', 'id');
    }

    public function level()
    {
        return $this->belongsTo(AreaLevel::class, 'level_id', 'id');
    }
    
    public function product(){
        return $this->belongsTo(Product::class,'product_id','id');
    }
}
