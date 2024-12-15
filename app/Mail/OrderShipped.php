<?php
namespace App\Mail;

use App\Models\Sosherl;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;

class OrderShipped extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Public properties to hold email content.
     */

    public $order;
    /**
     * Create a new message instance.
     *
     * @param string $emailMessage
     * @param string $emailSubject
     */
    public function __construct($order,)
    {
        $this->order = $order;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('waelyahya889@gmail.com', 'Artiva'),
            replyTo: [
                new Address('example@gmail.com', 'Abdo'),
            ],
            subject: 'Order Received',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.order',
            with: [
                'order' => $this->order,
                'social' => Sosherl::query()->first(),
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
