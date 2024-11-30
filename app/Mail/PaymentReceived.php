<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentReceived extends Mailable
{
    use Queueable, SerializesModels;

    public $reservation; // Property to hold the reservation data

    /**
     * Create a new message instance.
     *
     * @param $reservation
     */
    public function __construct($reservation)
    {
        $this->reservation = $reservation; // Store the reservation data for use in the email view
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Payment Received', // Subject of the email
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.payment_received', // The view to render for the email
            with: [
                'reservation' => $this->reservation, // Pass the reservation data to the email view
            ],
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
