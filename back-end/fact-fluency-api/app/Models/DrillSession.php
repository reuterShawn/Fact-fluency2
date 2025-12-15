<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DrillSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'assignment_id',
        'started_at',
        'completed_at',
        'total_problems',
        'correct_answers',
        'time_taken_seconds',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function assignment()
    {
        return $this->belongsTo(DrillAssignment::class);
    }

    public function responses()
    {
        return $this->hasMany(DrillResponse::class, 'session_id');
    }
}