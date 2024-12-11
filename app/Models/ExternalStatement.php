<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExternalStatement extends Model
{
    use HasFactory;

    protected $fillable = ['account_id', 'description', 'type', 'amount', 'transaction_date'];
    protected $dates = ['transaction_date'];


    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}

