<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\EmergencyRequest;
use App\Exports\DoctorsExport;
use App\Exports\PatientsExport;
use App\Exports\AppointmentsExport;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index()
    {
        $stats = [
            'total_doctors' => Doctor::where('approval_status', 'approved')->count(),
            'total_patients' => Patient::count(),
            'total_appointments' => Appointment::count(),
            'total_emergency' => EmergencyRequest::count(),
        ];

        return view('admin.reports.index', compact('stats'));
    }

    // DOCTORS REPORT
    public function doctorsPdf()
    {
        $pdf = Pdf::loadView('admin.reports.pdf.doctors', compact('doctors'));
        return $pdf->download('doctors-report-' . now()->format('Y-m-d') . '.pdf');
    }

    public function doctorsExcel()
    {
        return Excel::download(new DoctorsExport, 'doctors-report-' . now()->format('Y-m-d') . '.xlsx');
    }

    // PATIENTS REPORT
    public function patientsPdf()
    {
        $patients = Patient::with('user')->get();
        $pdf = Pdf::loadView('admin.reports.pdf.patients', compact('patients'));
        return $pdf->download('patients-report-' . now()->format('Y-m-d') . '.pdf');
    }

    public function patientsExcel()
    {
        return Excel::download(new PatientsExport, 'patients-report-' . now()->format('Y-m-d') . '.xlsx');
    }

    // APPOINTMENTS REPORT
    public function appointmentsPdf(Request $request)
    {
        $query = Appointment::with('doctor.user', 'patient.user');

        if ($request->filled('from_date')) {
            $query->whereDate('appointment_date', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('appointment_date', '<=', $request->to_date);
        }

        $appointments = $query->orderByDesc('appointment_date')->get();
        $pdf = Pdf::loadView('admin.reports.pdf.appointments', compact('appointments'));
        return $pdf->download('appointments-report-' . now()->format('Y-m-d') . '.pdf');
    }

    public function appointmentsExcel(Request $request)
    {
        return Excel::download(
            new AppointmentsExport($request->from_date, $request->to_date),
            'appointments-report-' . now()->format('Y-m-d') . '.xlsx'
        );
    }
}
