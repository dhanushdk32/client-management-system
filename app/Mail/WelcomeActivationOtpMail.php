<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeActivationOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $email;
    public $accountType; // 'Staff Member' or 'Client Portal'
    public $otpCode;
    public $companyOrRole;

    public function __construct($name, $email, $accountType, $otpCode, $companyOrRole = '')
    {
        $this->name = $name;
        $this->email = $email;
        $this->accountType = $accountType;
        $this->otpCode = $otpCode;
        $this->companyOrRole = $companyOrRole;
    }

    public function build()
    {
        return $this->subject('Welcome to IT Portal - Your Account Activation OTP Code: ' . $this->otpCode)
                    ->replyTo(config('mail.from.address'), config('mail.from.name'))
                    ->view('emails.welcome_activation_otp')
                    ->text('emails.welcome_activation_otp_plain');
    }
}
