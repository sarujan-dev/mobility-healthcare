<?php

namespace App\Exports;

use App\Models\Doctor;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DoctorsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Doctor::with('user')->where('approval_status', 'approved')->get();
    }

    public function headings(): array
    {
        return ['Name', 'Type', 'Phone', 'Email', 'Availability', 'SLMC No.'];
    }

    public function map($doctor): array
    {
        return [
            $doctor->user->name,
            $doctor->doctor_type,
            $doctor->user->phone ?? '-',
            $doctor->user->email,
            $doctor->availability_status,
            $doctor->slmc_reg_no ?? '-',
        ];
    }
}
