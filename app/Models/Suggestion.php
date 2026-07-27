<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Suggestion extends Model
{
    protected $fillable = [
        'word',
        'status',
        'ip',
        'user_id',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}