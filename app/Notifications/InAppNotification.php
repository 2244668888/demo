<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class InAppNotification extends Notification
{
    use Queueable;

    protected $screen;
    protected $action;
    protected $message;
    protected $datetime;
    protected $route;

    public function __construct($screen, $action, $message, $route, $datetime)
    {
        $this->screen = $screen;
        $this->action = $action;
        $this->message = $message;
        $this->datetime = $datetime;
        $this->route = $route;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'screen' => $this->screen,
            'action' => $this->action,
            'message' => $this->message,
            'datetime' => $this->datetime,
            'route' => $this->route,
            'created_at' => now()->toDateTimeString(),
        ];
    }
}
