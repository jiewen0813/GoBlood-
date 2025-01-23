<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LowBloodLevelNotification extends Notification
{
    use Queueable;

    private $bloodBank;
    private $bloodType;
    private $level;

    /**
     * Create a new notification instance.
     *
     * @param string $bloodBank
     * @param string $bloodType
     * @param int $level
     */
    public function __construct(string $bloodBank, string $bloodType, int $level)
    {
        $this->bloodBank = $bloodBank;
        $this->bloodType = $bloodType;
        $this->level = $level;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database']; // Includes both email and in-app notifications
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Low Blood Level Alert')
            ->line("The blood level for {$this->bloodType} is critically low.")
            ->line("Blood Bank: {$this->bloodBank}")
            ->line("Current Level: {$this->level}")
            ->action('View Inventory', url('/'))
            ->line('Your help can save lives!');
    }

    /**
     * Get the array representation of the notification for in-app notifications.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => "The blood level for {$this->bloodType} is critically low at {$this->bloodBank}. Current Level: {$this->level}.",
            'url' => url('/donate'),
            'blood_bank' => $this->bloodBank,
            'blood_type' => $this->bloodType,
            'level' => $this->level,
        ];
    }
}
