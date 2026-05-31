<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PracticeAnswer extends Model
{
    protected $fillable = [
        'practice_session_id', 'question_no', 'voltage', 'resistor_ohm',
        'student_answer', 'expected_answer', 'is_correct',
        'sensor_voltage', 'sensor_current', 'sensor_resistance', 'sensor_temp'
    ];

    protected $casts = [
        'is_correct' => 'boolean',
    ];

    public function session()
    {
        return $this->belongsTo(PracticeSession::class, 'practice_session_id');
    }
}
