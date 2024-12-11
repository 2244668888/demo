<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductionApi extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'production_id',
        'start_time',
        'end_time',
        'mc_no'
    ];
}
