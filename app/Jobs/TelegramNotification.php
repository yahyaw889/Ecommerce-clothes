<?php

namespace App\Jobs;

use App\Models\User;
use App\Notifications\OrderCreatedNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;


class TelegramNotification implements ShouldQueue
{
    use Dispatchable, Queueable, SerializesModels;

    protected $order;
    protected $users;

    public function __construct($order)
    {
        $this->order = $order;
    }

    public function handle()
    {
        // Send notification to users
        Notification::send(User::first(), new OrderCreatedNotification($this->order));
    }
}
