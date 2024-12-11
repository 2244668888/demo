<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderVerificationHistory extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class, 'action_by');
    }
}
