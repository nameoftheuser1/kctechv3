<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Reservation;

class ReservationNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $reservation;
    public $recipientType;

    /**
     * Create a new message instance.
     */
    public function __construct(Reservation $reservation, $recipientType)
    {
        $this->reservation = $reservation;
        $this->recipientType = $recipientType;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->recipientType === 'admin'
                ? 'New Reservation Notification'
                : 'Reservation Confirmation'
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $viewName = $this->recipientType === 'admin'
            ? 'emails.reservation_admin'
            : 'emails.reservation_user';

        return new Content(
            view: $viewName,
            with: ['reservation' => $this->reservation]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
