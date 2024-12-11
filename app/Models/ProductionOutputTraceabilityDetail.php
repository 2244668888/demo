<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductionOutputTraceabilityDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'machine_id',
        'pot_id',
        'start_time',
        'stop_time',
        'count',
        'status',
        'operator',
        'duration'
    ];

    public function machine(){
        return $this->belongsTo(Machine::class,'machine_id','id');
    }

    public function production(){
        return $this->belongsTo(ProductionOutputTraceability::class,'pot_id','id');
    }
}
