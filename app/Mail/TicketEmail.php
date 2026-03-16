<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

use Barryvdh\DomPDF\Facade\Pdf;

class TicketEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $customer;
    public $transactions;

    /**
     * Create a new message instance.
     */
    public function __construct($customer, $transactions)
    {
        $this->customer = $customer;
        $this->transactions = $transactions;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'E-Ticket Booking Successful',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.ticket',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $pdf = Pdf::loadView('pdf.ticket', [
            'customer' => $this->customer,
            'transactions' => $this->transactions
        ])->setPaper('A4', 'portrait');

        return [
            \Illuminate\Mail\Mailables\Attachment::fromData(
                fn () => $pdf->output(),
                'E-Ticket_'.$this->customer->name.'.pdf'
            )->withMime('application/pdf'),
        ];
    }
}
