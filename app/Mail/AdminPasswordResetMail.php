<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class AdminPasswordResetMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $token;
    public $resetUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, string $token)
    {
        $this->user = $user;
        $this->token = $token;
        $this->resetUrl = url('/admin/password/reset/' . $token);
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Admin Password Reset Request')
                    ->view('emails.admin-password-reset')
                    ->with([
                        'user' => $this->user,
                        'token' => $this->token,
                        'resetUrl' => $this->resetUrl
                    ]);
    }
}



