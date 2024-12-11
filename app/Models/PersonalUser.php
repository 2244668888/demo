<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PersonalUser extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'user_id',
        'gender',
        'marital_status',
        'dob',
        'age',
        'address',
        'ethnic',
        'personal_phone',
        'personal_mobile',
        'nric',
        'nationality',
        'sosco_no',
        'base_salary',
        'epf_no',
        'tin',
        'passport',
        'passport_expiry_date',
        'immigration_no',
        'immigration_no_expiry_date',
        'permit_no',
        'permit_no_expiry_date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
