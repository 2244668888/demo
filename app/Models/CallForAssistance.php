<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CallForAssistance extends Model
{
    use HasFactory, SoftDeletes;

    public function user()
    {
        return $this->belongsTo(User::class, 'attended_pic', 'id');
    }

    protected $fillable = [
        'datetime',
        'mc_no',
        'call',
        'package_no',
        'msg_no',
        'attended_datetime',
        'attended_pic',
        'remarks',
        'submitted_datetime',
        'image',
        'status'
    ];
}
