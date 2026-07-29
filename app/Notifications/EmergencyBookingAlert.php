<?php

namespace App\Notifications;

use App\Models\EmergencyRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class EmergencyBookingAlert extends Notification
{
    use Queueable;

    public function __construct(public EmergencyRequest $emergencyRequest)
    {
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $req = $this->emergencyRequest;

        return (new MailMessage)
            ->subject('🚨 URGENT: Emergency VOG Request - Mobility Health Care')
            ->greeting('Dr. ' . $notifiable->name . ',')
            ->line('You have received an EMERGENCY appointment request that requires immediate attention.')
            ->line('Patient: ' . $req->patient->user->name)
            ->line('Patient Phone: ' . ($req->patient->user->phone ?? 'Not provided'))
            ->line('Distance from you: ' . round($req->distance_km, 1) . ' km')
            ->line('Please log in to your dashboard immediately to confirm or contact the patient.')
            ->action('View Emergency Request', route('doctor.appointments.index'))
            ->salutation('Mobility Health Care System');
    }
}
