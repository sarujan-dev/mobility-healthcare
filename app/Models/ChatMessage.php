<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    use HasFactory;

    protected $fillable = ['patient_id', 'role', 'message'];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
