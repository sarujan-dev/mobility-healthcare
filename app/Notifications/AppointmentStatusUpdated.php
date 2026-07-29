<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class AppointmentStatusUpdated extends Notification
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
        $status = $appt->status;

        $message = (new MailMessage)
            ->subject('Appointment ' . $status . ' - Mobility Health Care')
            ->greeting('Hello ' . $notifiable->name . ',');

        if ($status === 'Confirmed') {
    $message->line('Great news! Your appointment has been confirmed by the doctor.')
        ->line('**Appointment ID:** APT-' . str_pad($appt->id, 4, '0', STR_PAD_LEFT))
        ->line('**Appointment Number:** #' . ($appt->appointment_number ?? '-'))
        ->line('Doctor: ' . $appt->doctor->user->name)
        ->line('Date: ' . \Carbon\Carbon::parse($appt->appointment_date)->format('d M Y'))
        ->line('Time: ' . \Carbon\Carbon::parse($appt->time_slot)->format('h:i A'));

    // Add location map + directions link if the doctor has shared their location
    if ($appt->doctor->current_lat && $appt->doctor->current_lng) {
        $lat = $appt->doctor->current_lat;
        $lng = $appt->doctor->current_lng;

        $staticMapUrl = "https://staticmap.openstreetmap.de/staticmap.php?center={$lat},{$lng}&zoom=15&size=600x300&markers={$lat},{$lng},red-pushpin";
        $directionsUrl = "https://www.google.com/maps/dir/?api=1&destination={$lat},{$lng}";

        $message->line('**Doctor Location:**')
            ->line('![Doctor Location Map](' . $staticMapUrl . ')')
            ->action('Get Directions on Google Maps', $directionsUrl);
    }

    $message->line('**Important:** Your appointment QR code is attached to this email as "appointment-qr.png". Please save it and show it to your doctor at the time of your checkup — the doctor will scan it to complete your visit.')
        ->line('If the doctor cannot scan the QR code, share this code with them for manual entry:')
        ->line('**' . $appt->qr_token . '**')
        ->line('Please arrive 10 minutes before your scheduled time.');

            try {
                $qrImage = file_get_contents('https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . urlencode($appt->qr_token));
                if ($qrImage !== false) {
                    $message->attachData($qrImage, 'appointment-qr.png', [
                        'mime' => 'image/png',
                    ]);
                }
            } catch (\Exception $e) {
                // If QR generation fails, email still sends without attachment
            }

        } elseif ($status === 'Cancelled') {
            $message->line('We\'re sorry, but your appointment has been cancelled.')
                ->line('Doctor: ' . $appt->doctor->user->name)
                ->line('Date: ' . \Carbon\Carbon::parse($appt->appointment_date)->format('d M Y'))
                ->line('Please book another appointment or contact.');
        } elseif ($status === 'Completed') {
            $message->line('Your appointment with ' . $appt->doctor->user->name . ' has been marked as completed.')
                ->line('Thank you for using Mobility Health Care System.');
        }

        return $message->salutation('Thank you, Mobility Health Care System');
    }
}
