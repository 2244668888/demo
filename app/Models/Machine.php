<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Machine extends Model
{
    use HasFactory, SoftDeletes;
    public function tonnage(){
        return $this->belongsTo(MachineTonnage::class, 'tonnage_id', 'id');
    }
}
