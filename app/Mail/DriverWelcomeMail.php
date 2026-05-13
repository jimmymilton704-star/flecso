<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DriverWelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $driver;
    public $plainPassword;

    public function __construct($driver, $plainPassword)
    {
        $this->driver = $driver;
        $this->plainPassword = $plainPassword;
    }

    public function build()
    {
        return $this->subject('Welcome to Flecso Driver Portal')
                    ->view('emails.driver-welcome');
    }
}