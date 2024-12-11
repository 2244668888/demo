<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Product extends Model
{
    use HasFactory,SoftDeletes;

    public function type_of_products(){
        return $this->belongsTo(TypeOfProduct::class, 'type_of_product', 'id');
    }

    public function units(){
        return $this->belongsTo(Unit::class, 'unit', 'id');
    }

    public function customers(){
        return $this->belongsTo(Customer::class, 'customer_name', 'id');
    }

    public function suppliers(){
        return $this->belongsTo(Supplier::class, 'supplier_name', 'id');
    }

    public function categories(){
        return $this->belongsTo(Category::class, 'category', 'id');
    }

    public function locations()
    {
        return $this->hasMany(Location::class);
    }

    public function reordering(){
        return $this->belongsTo(ProductReordering::class, 'id', 'product_id');
    }

    public function location(){
        return $this->belongsTo(Location::class, 'id', 'product_id');
    }

    public function amortization()
    {
        return $this->hasOne(Amortization::class, 'product_id', 'id');
    }

}
