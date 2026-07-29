<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SlmcRegistration extends Model
{
    use HasFactory;

    protected $fillable = ['slmc_reg_no', 'registered_name', 'doctor_type', 'is_used'];
}
