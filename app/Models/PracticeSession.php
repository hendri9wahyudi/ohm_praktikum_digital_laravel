<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PracticeSession extends Model
{
    protected $fillable = [
        'user_id', 'package_id', 'attendance', 'quiz_score', 'practical_score',
        'manual_total_score', 'total_score', 'analysis_text', 'graph_json', 'finished_at'
    ];

    protected $casts = [
        'attendance' => 'boolean',
        'graph_json' => 'array',
        'finished_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function answers()
    {
        return $this->hasMany(PracticeAnswer::class);
    }
}
