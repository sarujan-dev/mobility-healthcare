<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class PasswordResetOtp extends Notification
{
    use Queueable;

    public function __construct(public string $otp)
    {
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Password Reset Code - Mobility Health Care')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('We received a request to reset your password.')
            ->line('Your password reset code is:')
            ->line('# ' . $this->otp)
            ->line('This code will expire in 10 minutes.')
            ->line('If you did not request this, please ignore this email.');
    }
}
