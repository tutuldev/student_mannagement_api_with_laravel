<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordForgotNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Password Forget Notification')
            ->line('Click the button below to reset your password.')
            ->action('Forgot Password', url('http://localhost:8080/#/reset-password?token=' . $this->token))
            ->line('If you did not request this, please ignore this email.');
    }

    public function toArray(object $notifiable): array
    {
        return [];
    }
}
