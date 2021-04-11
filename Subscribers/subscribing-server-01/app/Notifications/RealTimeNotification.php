<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\BroadcastMessage;

class RealTimeNotification extends Notification implements ShouldBroadcast
{
    use Queueable;

    public  $message;

    public function __construct( $message)
    {
        $this->message = $message;
    }


    public function via($notifiable)
    {
        return ['broadcast'];
    }



    public function toBroadcast($notifiable): BroadcastMessage
    {
        // send broadcast meaasge 
        return new BroadcastMessage([
            'message' => $this->message
        ]);
    }



}
