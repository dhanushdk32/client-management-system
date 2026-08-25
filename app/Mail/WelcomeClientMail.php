<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Client;

class WelcomeClientMail extends Mailable
{
    use Queueable, SerializesModels;

    public $client;
    public $plainPassword;

    public function __construct(Client $client, $plainPassword)
    {
        $this->client = $client;
        $this->plainPassword = $plainPassword;
    }

    public function build()
    {
        return $this->subject('Account Credentials - Client Management Portal')
                    ->replyTo(config('mail.from.address'), config('mail.from.name'))
                    ->view('emails.welcome_client')
                    ->text('emails.welcome_client_plain');
    }
}
