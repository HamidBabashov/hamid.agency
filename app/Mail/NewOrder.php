<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewOrder extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Order $order) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('mail.order.subject', [
                'number' => $this->order->id,
                'service' => __('site.service_types.'.$this->order->service_type),
            ]),
            replyTo: [new Address($this->order->email, $this->order->name)],
        );
    }

    public function content(): Content
    {
        return new Content(markdown: 'mail.order');
    }
}
