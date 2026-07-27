<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountDeletionFeedback extends Model
{
    protected $table = 'account_deletion_feedbacks';
    protected $fillable = [
        'user_name',
        'user_email',
        'reason',
        'comment',
    ];
}