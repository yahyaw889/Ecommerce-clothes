<?php

namespace App\Jobs;

use App\Mail\OrderShipped;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Mail\Mailer;
use Illuminate\Queue\SerializesModels;

class MailNotification implements ShouldQueue
{
    use Dispatchable, Queueable, SerializesModels;

    protected $order;

    public function __construct($order)
    {
        $this->order = $order;
    }

    public function handle(Mailer $mailer)
    {
        $mailer->to($this->order->email)->send(new OrderShipped($this->order));
    }
}
