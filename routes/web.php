<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\DoctorSearchController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\DoctorController as AdminDoctorController;
use App\Http\Controllers\Admin\EmergencyController as AdminEmergencyController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\SlmcRegistrationController;
use App\Http\Controllers\Doctor\DashboardController as DoctorDashboard;
use App\Http\Controllers\Doctor\ProfileController as DoctorProfileController;
use App\Http\Controllers\Doctor\ScheduleController as DoctorScheduleController;
use App\Http\Controllers\Doctor\AppointmentController as DoctorAppointmentController;
use App\Http\Controllers\Doctor\PatientHistoryController;
use App\Http\Controllers\Patient\DashboardController as PatientDashboard;
use App\Http\Controllers\Patient\ProfileController as PatientProfileController;
use App\Http\Controllers\Patient\AppointmentController as PatientAppointmentController;
use App\Http\Controllers\Patient\EmergencyController;
use App\Http\Controllers\Patient\HistoryController;
use App\Http\Controllers\Patient\FavoriteController;
use App\Http\Controllers\Patient\ChatbotController;
use App\Http\Controllers\Auth\PasswordResetOtpController;
use App\Http\Controllers\Auth\SlmcVerificationController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// WELCOME PAGE
Route::get('/', [WelcomeController::class, 'index'])->name('welcome');
Route::post('/contact', [WelcomeController::class, 'submitContact'])->name('contact.submit');

// DASHBOARD REDIRECT
Route::middleware('auth')->get('/dashboard', function () {
    return match(Auth::user()->role) {
        'admin' => redirect()->route('admin.dashboard'),
        'doctor' => redirect()->route('doctor.dashboard'),
        'patient' => redirect()->route('patient.dashboard'),
        default => redirect('/'),
    };
})->name('dashboard');

// PASSWORD RESET OTP
Route::middleware('guest')->group(function () {
    Route::get('/forgot-password', [PasswordResetOtpController::class, 'showRequestForm'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetOtpController::class, 'sendOtp'])->name('password.email');
    Route::get('/reset-password/verify', [PasswordResetOtpController::class, 'showResetForm'])->name('password.reset-otp.form');
    Route::post('/reset-password/verify', [PasswordResetOtpController::class, 'resetPassword'])->name('password.reset-otp.submit');
    Route::post('/reset-password/resend', [PasswordResetOtpController::class, 'resendOtp'])->name('password.reset-otp.resend');
});

// OTP REGISTER VERIFY
Route::middleware('guest')->group(function () {
    Route::get('/register/verify-otp', [\App\Http\Controllers\Auth\RegisteredUserController::class, 'showOtpForm'])->name('register.verify-otp');
    Route::post('/register/verify-otp', [\App\Http\Controllers\Auth\RegisteredUserController::class, 'verifyOtp'])->name('register.verify-otp.submit');
    Route::post('/register/resend-otp', [\App\Http\Controllers\Auth\RegisteredUserController::class, 'resendOtp'])->name('register.resend-otp');
});

// SLMC LIVE VERIFICATION (during registration, guest access needed)
Route::post('/verify-slmc', [SlmcVerificationController::class, 'verify'])->name('slmc.verify');

// DOCTOR SEARCH (logged in users)
Route::middleware('auth')->group(function () {
    Route::get('/doctors', [DoctorSearchController::class, 'index'])->name('doctors.index');
    Route::get('/doctors/{doctor}', [DoctorSearchController::class, 'show'])->name('doctors.show');
});

// ADMIN ROUTES
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('admin.dashboard');

    Route::get('/doctors', [AdminDoctorController::class, 'index'])->name('admin.doctors.index');
    Route::get('/doctors/{doctor}', [AdminDoctorController::class, 'show'])->name('admin.doctors.show');
    Route::post('/doctors/{doctor}/approve', [AdminDoctorController::class, 'approve'])->name('admin.doctors.approve');
    Route::post('/doctors/{doctor}/reject', [AdminDoctorController::class, 'reject'])->name('admin.doctors.reject');

    Route::get('/emergency-requests', [AdminEmergencyController::class, 'index'])->name('admin.emergency.index');

    Route::get('/reports', [ReportController::class, 'index'])->name('admin.reports.index');
    Route::get('/reports/doctors/pdf', [ReportController::class, 'doctorsPdf'])->name('admin.reports.doctors.pdf');
    Route::get('/reports/doctors/excel', [ReportController::class, 'doctorsExcel'])->name('admin.reports.doctors.excel');
    Route::get('/reports/patients/pdf', [ReportController::class, 'patientsPdf'])->name('admin.reports.patients.pdf');
    Route::get('/reports/patients/excel', [ReportController::class, 'patientsExcel'])->name('admin.reports.patients.excel');
    Route::get('/reports/appointments/pdf', [ReportController::class, 'appointmentsPdf'])->name('admin.reports.appointments.pdf');
    Route::get('/reports/appointments/excel', [ReportController::class, 'appointmentsExcel'])->name('admin.reports.appointments.excel');

    Route::get('/contact-messages', [ContactMessageController::class, 'index'])->name('admin.contact-messages.index');
    Route::delete('/contact-messages/{contactMessage}', [ContactMessageController::class, 'destroy'])->name('admin.contact-messages.destroy');

    Route::get('/slmc', [SlmcRegistrationController::class, 'index'])->name('admin.slmc.index');
    Route::post('/slmc', [SlmcRegistrationController::class, 'store'])->name('admin.slmc.store');
    Route::delete('/slmc/{slmcRegistration}', [SlmcRegistrationController::class, 'destroy'])->name('admin.slmc.destroy');
});

// DOCTOR ROUTES
Route::middleware(['auth', 'role:doctor'])->prefix('doctor')->group(function () {
    Route::get('/dashboard', [DoctorDashboard::class, 'index'])->name('doctor.dashboard');

    Route::get('/profile', [DoctorProfileController::class, 'edit'])->name('doctor.profile.edit');
    Route::post('/profile', [DoctorProfileController::class, 'update'])->name('doctor.profile.update');
    Route::post('/availability', [DoctorProfileController::class, 'updateAvailability'])->name('doctor.availability.update');

    Route::get('/schedule', [DoctorScheduleController::class, 'index'])->name('doctor.schedule.index');
    Route::post('/schedule', [DoctorScheduleController::class, 'update'])->name('doctor.schedule.update');

    Route::get('/appointments', [DoctorAppointmentController::class, 'index'])->name('doctor.appointments.index');
    Route::post('/appointments/{appointment}/confirm', [DoctorAppointmentController::class, 'confirm'])->name('doctor.appointments.confirm');
    Route::post('/appointments/{appointment}/cancel', [DoctorAppointmentController::class, 'cancel'])->name('doctor.appointments.cancel');
    Route::get('/scan', [DoctorAppointmentController::class, 'scan'])->name('doctor.appointments.scan');
    Route::post('/scan/complete', [DoctorAppointmentController::class, 'completeScan'])->name('doctor.appointments.complete-scan');

    Route::get('/patients/{appointment}/history', [PatientHistoryController::class, 'show'])->name('doctor.patients.history');
    Route::post('/patients/{appointment}/note', [PatientHistoryController::class, 'storeNote'])->name('doctor.patients.note');
});

// PATIENT ROUTES
Route::middleware(['auth', 'role:patient'])->prefix('patient')->group(function () {
    Route::get('/dashboard', [PatientDashboard::class, 'index'])->name('patient.dashboard');

    Route::get('/profile', [PatientProfileController::class, 'edit'])->name('patient.profile.edit');
    Route::post('/profile', [PatientProfileController::class, 'update'])->name('patient.profile.update');

    Route::get('/appointments', [PatientAppointmentController::class, 'index'])->name('patient.appointments.index');
    Route::get('/book/{doctor}', [PatientAppointmentController::class, 'create'])->name('patient.appointments.create');
    Route::post('/book/{doctor}', [PatientAppointmentController::class, 'store'])->name('patient.appointments.store');
    Route::post('/appointments/{appointment}/cancel', [PatientAppointmentController::class, 'cancel'])->name('patient.appointments.cancel');

    Route::get('/emergency', [EmergencyController::class, 'index'])->name('patient.emergency.index');
    Route::post('/emergency/find', [EmergencyController::class, 'find'])->name('patient.emergency.find');
    Route::post('/emergency/book', [EmergencyController::class, 'book'])->name('patient.emergency.book');

    Route::get('/medical-history', [HistoryController::class, 'index'])->name('patient.history.index');

    Route::get('/favorites', [FavoriteController::class, 'index'])->name('patient.favorites.index');
    Route::post('/favorites/{doctor}/toggle', [FavoriteController::class, 'toggle'])->name('patient.favorites.toggle');

    Route::get('/chatbot', [ChatbotController::class, 'index'])->name('patient.chatbot.index');
    Route::post('/chatbot/send', [ChatbotController::class, 'send'])->name('patient.chatbot.send');
    Route::post('/chatbot/clear', [ChatbotController::class, 'clearHistory'])->name('patient.chatbot.clear');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
