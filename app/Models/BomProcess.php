<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BomProcess extends Model
{
    use HasFactory,SoftDeletes;

    public function process()
    {
        return $this->belongsTo(Process::class,'process_id','id');
    }

    public function machineTonnage()
    {
        return $this->belongsTo(MachineTonnage::class,'machine_tonnage_id','id');
    }
}
