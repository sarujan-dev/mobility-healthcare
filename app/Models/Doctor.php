<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'doctor_type', 'qualifications', 'about',
        'availability_status', 'profile_photo', 'rating',
        'lat', 'lng', 'current_lat', 'current_lng',
        'approval_status', 'slmc_reg_no',
    ];

    public function user() { return $this->belongsTo(User::class); }
    public function schedules() { return $this->hasMany(DoctorSchedule::class); }
    public function appointments() { return $this->hasMany(Appointment::class); }
    public function emergencyRequests() { return $this->hasMany(EmergencyRequest::class); }
}
