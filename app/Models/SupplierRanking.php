<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SupplierRanking extends Model
{
    use HasFactory,SoftDeletes;

    public function supplier()
    {
         return $this->belongsTo(Supplier::class, 'supplier_id', 'id');
    }
    public function user()
    {
         return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
