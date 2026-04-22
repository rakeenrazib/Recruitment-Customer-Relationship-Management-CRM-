<?php

namespace App\Mail;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InterviewScheduledMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Application $application) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Interview Scheduled — ' . $this->application->job->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.interview_scheduled',
            with: [
                'candidateName' => $this->application->user->name,
                'jobTitle'      => $this->application->job->title,
                'company'       => $this->application->job->company,
            ],
        );
    }
}
