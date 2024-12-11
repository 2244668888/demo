<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MoreUser extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'user_id',
        'emergency_contact_name',
        'emergency_contact_relationship',
        'emergency_contact_address',
        'emergency_contact_phone_no',
        'annual_leave',
        'annual_leave_balance_day',
        'carried_leave',
        'carried_leave_balance_day',
        'medical_leave',
        'medical_leave_balance_day',
        'unpaid_leave',
        'unpaid_leave_balance_day'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
