<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DrillResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'problem',
        'correct_answer',
        'student_answer',
        'is_correct',
        'response_time_ms',
    ];

    protected $casts = [
        'problem' => 'array',
        'is_correct' => 'boolean',
    ];

    public function session()
    {
        return $this->belongsTo(DrillSession::class);
    }
}