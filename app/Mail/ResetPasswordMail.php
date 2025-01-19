<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $resetLink; //property to hold hold reset link     

    /**
     * Create a new message instance.
     */
    public function __construct($resetLink)
    {
        $this->resetLink = $resetLink;
    }

    public function build()
    {
        return $this
            ->subject('Reset Your Password') 
            ->html("
                <html>
                    <head>
                        <title>Reset Password</title>
                    </head>
                    <body>
                        <p>Hello,</p>
                        <p>Click the link below to reset your password:</p>
                        <p><a href='{$this->resetLink}'>{$this->resetLink}</a></p>
                    </body>
                </html>
            "); 
    }
}
