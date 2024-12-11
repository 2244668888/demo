<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'account_id',
        'secondary_account_id', 
        'type', 
        'amount',
        'description',
        'customer_id', // Optional: link to a customer
        'supplier_id', // Optional: link to a supplier
        'product_id', // Optional: link to a product
        'created_at',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

}

