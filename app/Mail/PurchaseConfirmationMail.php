<?php

namespace App\Mail;

use App\Models\OrderItem;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PurchaseConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    private $order;

    /**
     * Create a new message instance.
     */
    public function __construct($order)
    {
        $this->order = $order;  
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Purchase Confirmation Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    { 
        $items = OrderItem::with(['product', 'order'])->where('order_id', $this->order->id)->get();
        
        return new Content(
            view: 'emails.purchase_confirmation',
            with: [
                'items' => $items->toArray()
            ]
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

    public function build()
    {
        return $this->view('emails.purchase_confirmation')
        ->subject('Purchase Confirmation');
    }
}
