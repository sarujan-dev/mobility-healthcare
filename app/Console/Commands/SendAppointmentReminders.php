<?php

namespace App\Console\Commands;

use App\Models\Appointment;
use App\Notifications\AppointmentReminder;
use Illuminate\Console\Command;

class SendAppointmentReminders extends Command
{
    protected $signature = 'appointments:send-reminders';
    protected $description = 'Send email reminders for appointments scheduled tomorrow';

    public function handle()
    {
        $tomorrow = now()->addDay()->toDateString();

        $appointments = Appointment::where('appointment_date', $tomorrow)
            ->where('status', 'Confirmed')
            ->with('patient.user', 'doctor.user') 
            ->get();

        foreach ($appointments as $appointment) {
            $appointment->patient->user->notify(new AppointmentReminder($appointment));
        }

        $this->info('Sent ' . $appointments->count() . ' appointment reminder(s) for ' . $tomorrow);
    }
}
