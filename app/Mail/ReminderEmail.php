<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Reminder;

class ReminderEmail extends Mailable {
    use Queueable, SerializesModels;

    // This will hold the reminder information
    public $reminder;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Reminder $reminder) {
        $this->reminder = $reminder;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope() {
        return new Envelope(
            subject: 'Upcoming Reminder: ' . $this->reminder->title, // Email subject
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content(): Content {
        return new Content(
            markdown: 'emails.reminder', // resources/views/emails/reminder.blade.php
            with: [ // Data to pass to the email view
                'reminderTitle' => $this->reminder->title,
                'reminderDescription' => $this->reminder->description,
                'reminderDueDate' => $this->reminder->reminder_at, // Or however you access the due date
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments() {
        return [];
    }
}
