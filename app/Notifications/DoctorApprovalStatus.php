<?php

namespace App\Notifications;

use App\Models\Doctor;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class DoctorApprovalStatus extends Notification
{
    use Queueable;

    public function __construct(public Doctor $doctor)
    {
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $message = (new MailMessage)
            ->greeting('Hello Dr. ' . $notifiable->name . ',');

        if ($this->doctor->approval_status === 'approved') {
            $message->subject('Account Approved - Mobility Health Care')
                ->line('Congratulations! Your doctor account has been verified and approved.')
                ->line('You can now log in and start managing your profile, schedule, and appointments.')
                ->action('Login Now', route('login'));
        } else {
            $message->subject('Account Verification Update - Mobility Health Care')
                ->line('We were unable to verify your account with the details provided.')
                ->line('Please contact our support team for more information, or re-register with correct SLMC details.');
        }

        return $message->salutation('Thank you, Mobility Health Care System');
    }
}
