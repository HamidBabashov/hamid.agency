<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/** Müştəriyə: sifarişinizin statusu dəyişdi */
class OrderStatusChanged extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Order $order) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: __('account.mail.status.subject', ['number' => $this->order->id]));
    }

    public function content(): Content
    {
        return new Content(markdown: 'mail.customer.order-status');
    }
}
