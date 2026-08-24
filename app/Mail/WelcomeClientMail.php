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
        return $this->subject('Welcome to Client Management System')
                    ->view('emails.welcome_client');
    }
}
