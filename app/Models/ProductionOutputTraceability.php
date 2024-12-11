<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductionOutputTraceability extends Model
{
    use HasFactory, SoftDeletes;

    public function daily_production(){
        return $this->belongsTo(DailyProductionPlanning::class,'dpp_id','id');
    }

    public function details(){
        return $this->hasMany(ProductionOutputTraceabilityDetail::class, 'id', 'pot_id');
    }

    public function product(){
        return $this->belongsTo(Product::class,'product_id','id');
    }

    public function machine(){
        return $this->belongsTo(Machine::class,'machine_id','id');
    }
}
