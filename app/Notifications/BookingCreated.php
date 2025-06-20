<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BookingCreated extends Notification
{
    use Queueable;

    protected $title;
    protected $message;

    public function __construct($message, $title = 'Status')
    {
        $this->message = $message;
        $this->title = $title;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
        ];
    }
}
