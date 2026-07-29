<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class AppointmentReminder extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Appointment $appointment)
    {
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $appt = $this->appointment;

        return (new MailMessage)
            ->subject('Reminder: Appointment Tomorrow - Mobility Health Care')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('This is a friendly reminder about your upcoming appointment tomorrow.')
            ->line('Doctor: ' . $appt->doctor->user->name)
            ->line('Date: ' . \Carbon\Carbon::parse($appt->appointment_date)->format('d M Y'))
            ->line('Time: ' . \Carbon\Carbon::parse($appt->time_slot)->format('h:i A'))
            ->line('Please remember to bring your QR code and arrive 10 minutes early.')
            ->salutation('Thank you, Mobility Health Care System');
    }
}
