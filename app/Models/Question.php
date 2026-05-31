<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = ['package_id', 'question_no', 'voltage', 'expected_current'];

    protected $casts = [
        'expected_current' => 'float',
    ];

    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}
