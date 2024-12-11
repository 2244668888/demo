<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class MaterialPlanning extends Model
{
    use HasFactory,SoftDeletes;

    public function details(){
        return $this->hasMany(MaterialPlanningDetail::class, 'planning_id', 'id');
    }
}
