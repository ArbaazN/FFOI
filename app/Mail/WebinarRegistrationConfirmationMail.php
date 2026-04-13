<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WebinarRegistrationConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $contact;

    public function __construct($registration, $webinar = null)
    {
        $this->contact = (object) [
            'name' => $registration->name,
            'fullname' => $registration->name,
            'email' => $registration->email,
            'message' => null,
            'webinar_title' => $webinar?->title,
            'meeting_link' => $webinar?->meeting_link,
        ];
    }

    public function build()
    {
        return $this->subject('Webinar Registration Confirmation')
            ->view('emails.webinar_registration_confirmation');
    }
}
