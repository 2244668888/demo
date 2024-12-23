<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DailyProductionChildPart extends Model
{
    use HasFactory, SoftDeletes;
    public function products(){
        return $this->belongsTo(Product::class,'product_id','id');
    }

    public function parent_products(){
        return $this->belongsTo(Product::class,'parent_part_id','id');
    }
}
