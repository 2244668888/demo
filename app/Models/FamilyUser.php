<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FamilyUser extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'user_id',
        'spouse_name',
        'family_dob',
        'family_age',
        'family_address',
        'family_phone',
        'family_mobile',
        'family_nric',
        'family_passport',
        'family_passport_expiry_date',
        'family_immigration_no',
        'family_immigration_no_expiry_date',
        'family_permit_no',
        'family_permit_no_expiry_date',
        'children_no',
        'attachment'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
