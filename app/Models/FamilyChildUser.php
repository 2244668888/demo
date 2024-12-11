<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FamilyChildUser extends Model
{
    use HasFactory,SoftDeletes;

    public function family()
    {
        return $this->belongsTo(FamilyUser::class, 'family_id', 'id');
    }
}
