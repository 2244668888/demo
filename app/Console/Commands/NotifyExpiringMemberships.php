<?php

namespace App\Console\Commands;

use App\Models\Membership;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class NotifyExpiringMemberships extends Command
{
    protected $signature = 'notify:expiring-memberships';
    protected $description = 'Notify members about expiring memberships';

    public function handle()
    {
        $memberships = Membership::where('expiry_date', '<=', now()->addDays(3))->get();

        foreach ($memberships as $membership) {
            Mail::to($membership->phone)->send(new \App\Mail\ExpiryNotificationMail($membership));
        }

        $this->info('Expiring memberships have been notified.');
    }
}
