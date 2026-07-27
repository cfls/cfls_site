<?php

namespace App\Mail;

use App\Models\Suggestion;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewSuggestionMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Suggestion $suggestion)
    {
    }

    public function build()
    {
        return $this->subject('Nouvelle suggestion de mot — LSFBGo')
            ->view('emails.suggestion')
            ->with([
                'suggestion' => $this->suggestion,
            ]);
    }
}