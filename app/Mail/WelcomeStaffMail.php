<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\StaffMember;

class WelcomeStaffMail extends Mailable
{
    use Queueable, SerializesModels;

    public $staff;
    public $plainPassword;

    public function __construct(StaffMember $staff, $plainPassword)
    {
        $this->staff = $staff;
        $this->plainPassword = $plainPassword;
    }

    public function build()
    {
        return $this->subject('Staff Portal Credentials - IT Operations Team')
                    ->replyTo(config('mail.from.address'), config('mail.from.name'))
                    ->view('emails.welcome_staff')
                    ->text('emails.welcome_staff_plain');
    }
}
