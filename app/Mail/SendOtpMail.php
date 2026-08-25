<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public $otpCode;

    public function __construct($otpCode)
    {
        $this->otpCode = $otpCode;
    }

    public function build()
    {
        return $this->subject('Password Reset OTP Code - Client Portal')
                    ->replyTo(config('mail.from.address'), config('mail.from.name'))
                    ->view('emails.send_otp')
                    ->text('emails.send_otp_plain');
    }
}
