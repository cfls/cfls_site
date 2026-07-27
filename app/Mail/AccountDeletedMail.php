<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class AccountDeletedMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;
    public string $surveyUrl;

    public function __construct(User $user)
    {
        $this->user = $user;

        $this->surveyUrl = URL::temporarySignedRoute(
            'deletion-survey.show',
            now()->addDays(14), // el link expira en 14 días
            [
                'name'  => $user->name,
                'email' => $user->email,
            ]
        );
    }

    public function build(): static
    {
        return $this->subject('Suppression de compte LSFBGO')
            ->view('emails.account-deleted');
    }
}