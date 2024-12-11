<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = ['name', 'price'];

// Service model
public function memberships()
{
    return $this->belongsToMany(Membership::class, 'membership_services')->withPivot('price');
}

public function salePrice()
{
    return $this->hasOne(SalePrice::class); 
}


}
