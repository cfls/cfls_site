<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordResetCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public User $user, public string $verificationCode) {}

    public function build(): self
    {
        return $this->subject('Réinitialisation de votre mot de passe LSFBGO')
            ->view('emails.code.password-reset');
    }
}
