<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SportSection extends Model
{
    protected $table = 'sport_sections';

    protected $fillable = ['number', 'title', 'subtitle', 'page', 'sort_order'];

    public function subsections(): HasMany
    {
        return $this->hasMany(SportSubsection::class)->orderBy('sort_order');
    }
}