<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vimeo extends Model
{
    public static function boot()
    {
        parent::boot();

        static::creating(function ($vimeo) {
            $vimeo->users_id = Auth::id(); // Automatically set the logged-in user's ID
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'categories_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function getHasSubtitledVersionAttribute(): bool
    {
        return !empty($this->url_cloudinary_subtitled);
    }

}
