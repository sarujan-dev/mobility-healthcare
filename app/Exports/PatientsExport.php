<?php

namespace App\Exports;

use App\Models\Patient;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PatientsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Patient::with('user')->get();
    }

    public function headings(): array
    {
        return ['Name', 'Email', 'Phone', 'Gender', 'DOB', 'Address', 'Registered On'];
    }

    public function map($patient): array
    {
        return [
            $patient->user->name,
            $patient->user->email,
            $patient->user->phone ?? '-',
            $patient->gender ?? '-',
            $patient->dob ?? '-',
            $patient->address ?? '-',
            $patient->created_at->format('d M Y'),
        ];
    }
}
