<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendCodeResetPassword extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $code;
    public $appUrl;

    public function __construct($code, $appUrl)
    {
        $this->code = $code;
        $this->appUrl = $appUrl;
    }

    public function build()
    {
        return $this->markdown('emails.sendCodeResetPassword')
            ->subject("Wachtwoord resetten");
    }
}