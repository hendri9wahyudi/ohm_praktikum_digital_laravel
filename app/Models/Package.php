<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $fillable = ['code', 'resistor_ohm', 'title'];

    public function questions()
    {
        return $this->hasMany(Question::class);
    }
}
