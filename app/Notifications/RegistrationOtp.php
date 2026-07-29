<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class RegistrationOtp extends Notification
{
    use Queueable;

    public function __construct(public string $otp, public string $name)
    {
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Verify Your Email - Mobility Health Care')
            ->greeting('Hello ' . $this->name . ',')
            ->line('Thank you for registering with Mobility Health Care System.')
            ->line('Your email verification code is:')
            ->line('# ' . $this->otp)
            ->line('This code will expire in 10 minutes.')
            ->line('If you did not request this, please ignore this email.');
    }
}
