<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentReminderNotification extends Notification
{
    use Queueable;

    private $appointment;

    public function __construct($appointment)
    {
        $this->appointment = $appointment;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Appointment Reminder')
            ->line('This is a reminder for your upcoming blood donation appointment.')
            ->line('Date: ' . $this->appointment->appointment_date->format('Y-m-d'))
            ->line('Time: ' . $this->appointment->time_slot)
            ->line('Location: ' . $this->appointment->bloodBankAdmin->name)
            ->action('View Appointment', url('/appointments/'))
            ->line('Don\'t forget to fill in your health details. Thank you for donating blood!');
    }

    public function toArray($notifiable)
    {
        return [
            'message' => 'Reminder: Your appointment is scheduled for today on ' . 
                         $this->appointment->appointment_date->format('d M Y') . 
                         ' at ' . $this->appointment->time_slot . 
                         ' in ' . $this->appointment->bloodBankAdmin->name . 
                         '. Don\'t forget to fill in your health details.',
        ];
    }
}
