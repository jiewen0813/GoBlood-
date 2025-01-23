<?php

namespace App\Notifications;

use App\Models\BloodRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewBloodRequest extends Notification
{
    use Queueable;

    protected $bloodRequest;

    public function __construct(BloodRequest $bloodRequest)
    {
        $this->bloodRequest = $bloodRequest;
    }

    // Define the channels for the notification
    public function via($notifiable)
    {
        return ['mail', 'database']; // Send via email and database (in-app)
    }

    // Send email notification
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('A new blood request has been made!')
            ->line('Blood Type: ' . $this->bloodRequest->blood_type)
            ->line('Request Location: ' . $this->bloodRequest->location)
            ->line('Request Time: ' . $this->bloodRequest->created_at->format('F j, Y, g:i a')) 
            ->action('View Request', url('/'))
            ->line('Give blood, save lives!');
    }

    // Store in-app notification
    public function toDatabase($notifiable)
    {
        return [
            'request_id' => $this->bloodRequest->id,
            'blood_type' => $this->bloodRequest->blood_type,
            'location' => $this->bloodRequest->location,
            'message' => 'A new blood request has been made. Check it out!',
            
        ];
    }
}
