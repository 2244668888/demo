<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductionOutputTraceabilityShift extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'production_id',
        'dpp_id',
        'time',
        'total_qty',
        'reject_qty',
        'good_qty',
        'remarks'
    ];
}
