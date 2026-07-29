<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsultationNote extends Model
{
    use HasFactory;

    protected $fillable = ['appointment_id', 'doctor_id', 'patient_id', 'diagnosis', 'prescription'];

    public function appointment() { return $this->belongsTo(Appointment::class); }
    public function doctor() { return $this->belongsTo(Doctor::class); }
    public function patient() { return $this->belongsTo(Patient::class); }
}
