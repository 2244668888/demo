<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class MachineTonnage extends Model
{
    use HasFactory, SoftDeletes;

    public function machine(){
        return $this->belongsTo(Machine::class, 'machine_id', 'id');
    }

    public function daily_production_planning(){
        return $this->belongsTo(DailyProductionPlanning::class, 'dpp_id', 'id');
    }
}
