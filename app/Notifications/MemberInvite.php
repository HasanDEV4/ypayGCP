<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MemberInvite extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return  ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('QR System', $this->data['first_name'] . ' ' . $this->data['last_name'])
            ->greeting('Welcome, Member')
            // ->action('Link', route('login.show'))
            ->line('Email: ' . $this->data['email'])
            ->line('Password: ' . $this->data['password'])
            ->line('Thank you')
            ->line('QR System')
            ->salutation(' ');
    }
}
