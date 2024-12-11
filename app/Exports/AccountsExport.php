<?php

namespace App\Exports;

use App\Models\Account;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class AccountsExport implements FromView
{
    protected $accounts;

    public function __construct($accounts)
    {
        $this->accounts = $accounts;
    }

    public function view(): View
    {
        return view('accounting.accounts.export', [
            'accounts' => $this->accounts
        ]);
    }
}

