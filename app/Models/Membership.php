<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    protected $fillable = ['ref_no', 'member_name', 'phone', 'issue_date', 'expiry_date'];

    // Membership model
    public function services()
    {
        return $this->belongsToMany(Service::class, 'membership_services')->withPivot('price');
    }
}
