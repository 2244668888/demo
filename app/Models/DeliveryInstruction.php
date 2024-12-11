<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeliveryInstruction extends Model
{
    use HasFactory, softDeletes;

    public function order(){
        return $this->belongsTo(Order::class,'order_id','id');
    }

    public function user(){
        return $this->belongsTo(User::class,'created_by','id');
    }

    public function delivery_instruction_details(){
        return $this->hasMany(DeliveryInstructionDetail::class,'di_id','id');
    }
}
