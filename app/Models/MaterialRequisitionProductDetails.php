<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MaterialRequisitionProductDetails extends Model
{
    use HasFactory,SoftDeletes;
    public function area()
    {
        return $this->belongsTo(Area::class, 'area', 'id');
    }

    public function rack()
    {
        return $this->belongsTo(AreaRack::class, 'rack', 'id');
    }

    public function level()
    {
        return $this->belongsTo(AreaLevel::class, 'level', 'id');
    }
}
