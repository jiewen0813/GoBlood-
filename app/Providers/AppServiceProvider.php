<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use App\Models\Appointment;
use App\Observers\AppointmentObserver;
use App\Models\BloodBankAdmin;
use App\Observers\BloodBankObserver;
use App\Models\Donation;
use App\Observers\DonationObserver;
use App\View\Components\HealthDetailModal;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register the Appointment observer
        Appointment::observe(AppointmentObserver::class);

        //Register the BloodBankAdmin observer
        BloodBankAdmin::observe(BloodBankObserver::class);

        // Register the HealthDetailModal Blade component
        Blade::component('health-detail-modal', HealthDetailModal::class);

        Donation::observe(DonationObserver::class);
    }
}
