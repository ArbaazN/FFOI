<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

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
            'session_date' => $webinar?->date ? Carbon::parse($webinar->date)->format('d M Y') : null,
            'session_time' => $this->formatSessionTime($webinar?->time_from, $webinar?->time),
            'mode' => $webinar?->mode,
        ];
    }

    private function formatSessionTime(?string $from, ?string $to): ?string
    {
        if (! $from && ! $to) {
            return null;
        }

        $formattedFrom = $from ? Carbon::createFromFormat('H:i', substr($from, 0, 5))->format('h:i A') : null;
        $formattedTo = $to ? Carbon::createFromFormat('H:i', substr($to, 0, 5))->format('h:i A') : null;

        if ($formattedFrom && $formattedTo) {
            return $formattedFrom . ' - ' . $formattedTo;
        }

        return $formattedFrom ?: $formattedTo;
    }

    public function build()
    {
        return $this->subject('Webinar Registration Confirmation')
            ->view('emails.webinar_registration_confirmation');
    }
}
