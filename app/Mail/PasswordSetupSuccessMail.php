<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordSetupSuccessMail extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $email;
    public $loginUrl;
    public $accountType;

    public function __construct($name, $email, $loginUrl, $accountType = 'Portal User')
    {
        $this->name = $name;
        $this->email = $email;
        $this->loginUrl = $loginUrl;
        $this->accountType = $accountType;
    }

    public function build()
    {
        return $this->subject('Account Activated - Your Password Has Been Successfully Set')
                    ->replyTo(config('mail.from.address'), config('mail.from.name'))
                    ->view('emails.password_setup_success')
                    ->text('emails.password_setup_success_plain');
    }
}
