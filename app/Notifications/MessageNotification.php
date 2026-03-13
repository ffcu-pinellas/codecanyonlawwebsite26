<?php

namespace App\Notifications;

use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MessageNotification extends Notification
{
    use Queueable;

    public $message;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $senderName = $this->message->user->name;
        
        return (new MailMessage)
                    ->subject('New Message Received - ' . config('app.name'))
                    ->greeting('Hello ' . $notifiable->name . ',')
                    ->line('You have received a new secure message from ' . $senderName . '.')
                    ->line('Message Preview: "' . substr($this->message->text, 0, 100) . (strlen($this->message->text) > 100 ? '...' : '') . '"')
                    ->action('View Message', route('login'))
                    ->line('Thank you for using our legal/CPA portal!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'message_id' => $this->message->id,
            'sender_id' => $this->message->user_id,
        ];
    }
}
