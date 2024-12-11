<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bom extends Model
{
    use HasFactory,SoftDeletes;

    public function products(){
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
    public function subParts()
    {
        return $this->hasMany(BomSubPart::class, 'bom_id');
    }

    public function purchaseParts()
    {
        return $this->hasMany(BomPurchasePart::class, 'bom_id');
    }

    public function crushings()
    {
        return $this->hasMany(BomCrushing::class, 'bom_id');
    }

    public function processes()
    {
        return $this->hasMany(BomProcess::class, 'bom_id');
    }
}
