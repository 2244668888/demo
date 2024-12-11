<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Account extends Model
{
    protected $fillable = ['code', 'name', 'type', 'opening_balance', 'category_id', 'categoryType'];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
    
    public function category()
    {
        return $this->belongsTo(AccountCategory::class);
    }


    public function calculateBalance()
    {
        $debitTotal = $this->transactions()->where('type', 'debit')->sum('amount');
        $creditTotal = $this->transactions()->where('type', 'credit')->sum('amount');

        if (in_array($this->type, ['asset', 'expense'])) {
            return $this->opening_balance + ($debitTotal - $creditTotal);
        } else {
            return $this->opening_balance + ($creditTotal - $debitTotal);
        }
    }

    public function calculateBalanceDate($startDate = null, $endDate = null)
    {
        $debitTotal = DB::table('transactions')
            ->where('account_id', $this->id)
            ->where('type', 'debit')
            ->when($startDate, function ($query) use ($startDate) {
                return $query->whereDate('created_at', '>=', $startDate);
            })
            ->when($endDate, function ($query) use ($endDate) {
                return $query->whereDate('created_at', '<=', $endDate);
            })
            ->sum('amount');

        $creditTotal = DB::table('transactions')
            ->where('account_id', $this->id)
            ->where('type', 'credit')
            ->when($startDate, function ($query) use ($startDate) {
                return $query->whereDate('created_at', '>=', $startDate);
            })
            ->when($endDate, function ($query) use ($endDate) {
                return $query->whereDate('created_at', '<=', $endDate);
            })
            ->sum('amount');

        if (in_array($this->type, ['asset', 'expense'])) {
            return $this->opening_balance + ($debitTotal - $creditTotal);
        } else {
            return $this->opening_balance + ($creditTotal - $debitTotal);
        }
    }
}
