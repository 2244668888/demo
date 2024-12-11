<?php

namespace App\Mail;

use App\Models\Membership;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ExpiryNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $membership;

    public function __construct(Membership $membership)
    {
        $this->membership = $membership;
    }

    public function build()
    {
        return $this->subject('Membership Expiry Notification')
                    ->view('emails.membership_expiry');
    }
}
