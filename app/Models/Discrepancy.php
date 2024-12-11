<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Discrepancy extends Model
{
    use HasFactory, SoftDeletes;
    public function mrf()
    {
        return $this->belongsTo(MaterialRequisition::class, 'mrf_tr_id', 'id');
    }

    public function tr()
    {
        return $this->belongsTo(TransferRequest::class, 'mrf_tr_id', 'id');
    }

    public function products()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }


}
