<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id', 'doctor_id', 'appointment_date',
        'time_slot', 'type', 'status', 'notes',
        'qr_token', 'appointment_number',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function consultationNote()
    {
        return $this->hasOne(ConsultationNote::class);
    }
}
