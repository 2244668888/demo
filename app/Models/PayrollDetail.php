<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PayrollDetail extends Model
{
    use HasFactory,SoftDeletes;

    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function created_by_user(){
        return $this->belongsTo(User::class,'created_by','id');
    }
}
