<?php

namespace App\Notifications;

use App\Models\BloodDonationEvent;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;

class NewBloodDonationEvent extends Notification
{
    use Queueable;

    protected $event;

    public function __construct(BloodDonationEvent $event)
    {
        $this->event = $event;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    // Send email notification
    public function toMail($notifiable)
    {
        return (new MailMessage)
        ->line('A new blood donation event has been added!')
        ->line('Event Name: ' . $this->event->eventName)
        ->line('Date: ' . $this->event->eventDate->format('F j, Y'))
        ->line('Location: ' . $this->event->eventLocation)
        ->action('View Event', url('/events/'))
        ->line('Thank you for supporting the event!');
    }

    // Store in-app notification
    public function toDatabase($notifiable)
    {
        return [
            'event_name' => $this->event->eventName,
            'event_date' => $this->event->eventDate,
            'location' => $this->event->eventLocation,
            'message' => 'A new blood donation event has been added. Check it out!',
            'event_url' => url('/events/'), 
        ];
    }
}