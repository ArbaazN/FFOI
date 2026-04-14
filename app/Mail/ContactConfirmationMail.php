<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $contact;
    public string $type;

    public function __construct($contact, string $type = 'contact_us')
    {
        $this->contact = $contact;
        $this->type = $type;
    }

    public function build()
    {
        $config = match ($this->type) {
            'partner_with_us' => [
                'subject' => 'Thank You for Your Partnership Interest',
                'view' => 'emails.partner_confirmation',
            ],
            default => [
                'subject' => 'Thank You for Contacting Us',
                'view' => 'emails.contact_confirmation',
            ],
        };

        return $this->subject($config['subject'])
                    ->view($config['view']);
    }
}
