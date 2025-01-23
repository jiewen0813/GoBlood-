<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Console\Scheduling\Schedule;
use App\Models\Appointment;
use App\Notifications\AppointmentReminderNotification;

// Define your Artisan command
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule tasks
app(Schedule::class)->command('inspire')->hourly();

app(Schedule::class)->call(function () {
    $today = now()->toDateString();

    // Fetch today's appointments
    $appointments = Appointment::whereDate('appointment_date', $today)->get();

    foreach ($appointments as $appointment) {
        $user = $appointment->user;
        if ($user) {
            $user->notify(new AppointmentReminderNotification($appointment));
            \Log::info("Notification sent to user: {$user->email} for appointment ID: {$appointment->id}");
        }
    }
})->dailyAt('07:00');