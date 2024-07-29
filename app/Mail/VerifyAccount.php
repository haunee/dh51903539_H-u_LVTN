<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VerifyAccount extends Mailable
{
    use Queueable, SerializesModels;


    public $account;
    public $token;
    public function __construct($acc,$token)
    {
        //gán 2 tham số vào đây
        $this -> account = $acc;//customer
        $this -> token = $token;//verifytoken 
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Verify Account',
        );
    }

    public function build()
    {
        return $this->view('shop.mail.verify_account')
                    ->subject('Verify your account')
                    ->with([
                    'customer' => $this->account,
                    'token' => $this->token,
        ]);
    }
    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'shop.mail.verify_account',
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
