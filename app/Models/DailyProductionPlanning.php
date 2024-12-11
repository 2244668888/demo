<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DailyProductionPlanning extends Model
{
    use HasFactory,SoftDeletes;

    public function order()
    {
        return $this->belongsTo(Order::class,'order_id','id');
    }
    public function users(){
        return $this->belongsTo(User::class,'created_by','id');
    }
}
