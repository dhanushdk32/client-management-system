<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class PasswordChangedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $changedAt;

    public function __construct($user, $changedAt = null)
    {
        $this->user = $user;
        $this->changedAt = $changedAt ?? Carbon::now('Asia/Kolkata')->format('D, M j, Y g:i A');
    }

    public function build()
    {
        return $this->subject('Security Alert: Your Password Was Successfully Changed')
                    ->replyTo(config('mail.from.address'), config('mail.from.name'))
                    ->view('emails.password_changed')
                    ->text('emails.password_changed_plain');
    }
}
