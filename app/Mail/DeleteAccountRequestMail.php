<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DeleteAccountRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public $email;
    public $confirmationUrl;

    public function __construct($email, $confirmationUrl)
    {
        $this->email = $email;
        $this->confirmationUrl = $confirmationUrl;
    }

    public function build()
    {
        return $this->subject('Confirm Your Flecso Account Deletion Request')
            ->view('emails.delete_account_request');
    }
}