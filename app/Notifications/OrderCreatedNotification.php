<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Models\Order;
use Illuminate\Support\Facades\Log;
use NotificationChannels\Telegram\TelegramChannel;
use NotificationChannels\Telegram\TelegramMessage;

class OrderCreatedNotification extends Notification
{
    use Queueable;

    protected $order;

    /**
     * Create a new notification instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return [ TelegramChannel::class];
    }

    public function toTelegram($notifiable)
    {
        try {
            $chatId = env('TELEGRAM_CHAT_ID');
            $url = url('/artiva/orders/' . $this->order->id . '/edit');

            return TelegramMessage::create()
                ->to($chatId)
                ->content("You have a new order\n" . $this->order['first_name'] . ' ' . $this->order['last_name'] . " View Order New \n")
                ->line($url);
        } catch (\Exception $e) {
            Log::error('Telegram Notification Error: ' . $e->getMessage());
            return null;
        }
    }






}
