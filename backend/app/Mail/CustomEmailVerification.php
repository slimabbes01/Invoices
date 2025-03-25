<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CustomEmailVerification extends Mailable
{
    use Queueable, SerializesModels;

    public User $user; // Type-hinting for better clarity
    public string $verificationToken; // Type-hinting for better clarity

    public function __construct(User $user, string $verificationToken)
    {
        $this->user = $user;
        $this->verificationToken = $verificationToken;
    }

    public function build()
    {
        return $this->view('emails.verification') // The view for the email
        ->with([
            'verificationUrl' => url('/api/verify-email/' . $this->verificationToken), // URL to verify email
            'userName' => $this->user->name, // User's name for the email
        ])
            ->subject('Email Verification'); // You can set a subject for the email
    }
}
