<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminContactNotificationMail extends Mailable
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
        $subject = $this->type === 'partner_with_us'
            ? 'New Partner With Us Submission'
            : 'New Contact Form Submission';

        return $this->subject($subject)
                    ->view('emails.admin_contact_notification');
    }
}
