<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveDate extends Model
{
    use HasFactory;

    protected $fillable = ['doctor_id', 'leave_date', 'reason'];

    public function doctor() { return $this->belongsTo(Doctor::class); }
}
