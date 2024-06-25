<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VerifyAdminPass extends Mailable
{
    use Queueable, SerializesModels;
    public $admin;
    public $token;
    /**
     * Create a new message instance.
     */
    public function __construct($admin,$token)
    {
        $this -> admin = $admin;
        $this -> token = $token;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Verify Admin Pass',
        );
    }


    public function build()
    {
        return $this->view('admin.account.mail-forgot')
                    ->subject('Verify your account')
                    ->with([
                    'admin' => $this->admin,
                    'token' => $this->token,
                    ]);
    }


    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'admin.account.mail-forgot',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
