<?php

namespace App\Exports;

use App\Models\Appointment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AppointmentsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $fromDate;
    protected $toDate;

    public function __construct($fromDate = null, $toDate = null)
    {
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
    }

    public function collection()
    {
        $query = Appointment::with('doctor.user', 'patient.user');

        if ($this->fromDate) {
            $query->whereDate('appointment_date', '>=', $this->fromDate);
        }
        if ($this->toDate) {
            $query->whereDate('appointment_date', '<=', $this->toDate);
        }

        return $query->orderByDesc('appointment_date')->get();
    }

    public function headings(): array
    {
        return ['Patient', 'Doctor', 'Date', 'Time', 'Type', 'Status', 'Notes'];
    }

    public function map($appt): array
    {
        return [
            $appt->patient->user->name ?? '-',
            $appt->doctor->user->name ?? '-',
            \Carbon\Carbon::parse($appt->appointment_date)->format('d M Y'),
            \Carbon\Carbon::parse($appt->time_slot)->format('h:i A'),
            $appt->type,
            $appt->status,
            $appt->notes ?? '-',
        ];
    }
}
