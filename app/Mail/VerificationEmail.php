<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\User;

class VerificationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $password;
    
    /**
     * Create a new message instance.
     *
     * @param  User  $user
     * @param  string  $password
     * @return void
     */
    public function __construct(User $user, $password)
    {
        $this->user = $user;
        $this->password = $password; // Store the plain text password
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
{
    return $this->subject('Email Verification')
                ->view('emails.verify')
                ->with([
                    'user' => $this->user,
                    'verificationUrl' => url('/api/email/verify/' . $this->user->verification_token), // Use the random token
                    'password' => $this->password, // Pass the plain text password
                ]);
}


}
