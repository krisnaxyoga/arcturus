<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class HotelVerifification extends Mailable
{
    use Queueable, SerializesModels;
    public $data;
    public $member;
    /**
     * Create a new message instance.
     */
    public function __construct($data, $member)
    {
        $this->data = $data;
        $this->member = $member;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Hotel Verifification',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return (new Content)->view('emails.hotel_verification')->with(['data' => $this->data, 'member' => $this->member]);
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
