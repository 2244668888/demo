<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payroll extends Model
{
    use HasFactory,SoftDeletes;

    public function payrollDetails()
    {
        return $this->hasMany(PayrollDetail::class, 'payroll_id', 'id'); 
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
