<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasFactory, SoftDeletes;

    public function outgoings(){
        return $this->hasMany(Outgoing::class, 'id', 'outgoing_id');
    }

    public function users(){
        return $this->belongsTo(User::class,'created_by','id');
    }

    public function invoiceDetails()
    {
        return $this->hasMany(InvoiceDetail::class, 'invoice_id', 'id'); 
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
