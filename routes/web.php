<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BloodBankAdminController;
use App\Http\Controllers\BloodBankAdminDashboardController;
use App\Http\Controllers\BloodDonationEventController;
use App\Http\Controllers\EducationController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\BloodBankAdminAppointmentController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\PublicEventController;
use App\Http\Controllers\InventoryController;
use App\Http\Middleware\RedirectIfNotAuthenticatedBloodBankAdmin;
use App\Http\Controllers\RewardController;
use App\Http\Controllers\BloodRequestController;
use App\Http\Controllers\NotificationController;

Route::get('/', [WelcomeController::class, 'index'])->name('welcome'); 
Route::get('educations', [EducationController::class, 'indexForUsers'])->name('educations.index');
Route::get('educations/{education}', [EducationController::class, 'showForUsers'])->name('educations.show');
Route::get('/blood-requests/create', [BloodRequestController::class, 'create'])->name('blood_requests.create');
Route::get('/blood-requests', [BloodRequestController::class, 'index'])->name('blood_requests.index');
Route::post('/', [BloodRequestController::class, 'store'])->name('blood_requests.store');
Route::patch('/{id}/status', [BloodRequestController::class, 'updateStatus'])->name('blood_requests.updateStatus');

// Blood Bank Admin login routes
Route::prefix('blood-bank-admin')->group(function () {
    Route::get('/login', [BloodBankAdminController::class, 'showLoginForm'])->name('blood_bank_admin.login');
    Route::post('/login', [BloodBankAdminController::class, 'login'])->name('blood_bank_admin.login.submit');
    Route::post('/', [BloodBankAdminController::class, 'logout'])->name('blood_bank_admin.logout');
    
    Route::middleware([RedirectIfNotAuthenticatedBloodBankAdmin::class])->group(function () {
        Route::get('/dashboard', [BloodBankAdminDashboardController::class, 'index'])->name('blood_bank_admin.dashboard');
        Route::get('blood-donation-events', [BloodDonationEventController::class, 'index'])->name('blood_bank_admin.blood_donation_events.index');
        Route::get('blood-donation-events/create', [BloodDonationEventController::class, 'create'])->name('blood_bank_admin.blood_donation_events.create');
        Route::post('blood-donation-events', [BloodDonationEventController::class, 'store'])->name('blood_bank_admin.blood_donation_events.store');
        Route::get('blood-donation-events/{eventID}/edit', [BloodDonationEventController::class, 'edit'])->name('blood_bank_admin.blood_donation_events.edit');
        Route::put('blood-donation-events/{eventID}', [BloodDonationEventController::class, 'update'])->name('blood_bank_admin.blood_donation_events.update');
        Route::delete('blood-donation-events/{eventID}', [BloodDonationEventController::class, 'destroy'])->name('blood_bank_admin.blood_donation_events.destroy');
        Route::get('educations', [EducationController::class, 'index'])->name('blood_bank_admin.educations.index');
        Route::get('educations/create', [EducationController::class, 'create'])->name('blood_bank_admin.educations.create'); 
        Route::post('educations', [EducationController::class, 'store'])->name('blood_bank_admin.educations.store');
        Route::get('educations/{education}/edit', [EducationController::class, 'edit'])->name('blood_bank_admin.educations.edit'); 
        Route::put('educations/{education}', [EducationController::class, 'update'])->name('blood_bank_admin.educations.update');
        Route::delete('educations/{education}', [EducationController::class, 'destroy'])->name('blood_bank_admin.educations.destroy');
        Route::get('/appointments/today', [BloodBankAdminAppointmentController::class, 'todayAppointments'])->name('blood_bank_admin.appointments.today');
        Route::post('/appointments/{id}/status', [BloodBankAdminAppointmentController::class, 'updateStatus'])->name('blood_bank_admin.appointments.updateStatus');
        Route::get('/appointments/history', [BloodBankAdminAppointmentController::class, 'history'])->name('blood_bank_admin.appointments.history');
        Route::get('/donations', [DonationController::class, 'index'])->name('blood_bank_admin.donations.index');
        Route::get('/donations/create', [DonationController::class, 'create'])->name('blood_bank_admin.donations.create');
        Route::post('/donations', [DonationController::class, 'store'])->name('blood_bank_admin.donations.store');
        Route::get('donations/{donation}/edit', [DonationController::class, 'edit'])->name('blood_bank_admin.donations.edit');
        Route::put('donations/{donation}', [DonationController::class, 'update'])->name('blood_bank_admin.donations.update');
        Route::delete('donations/{donation}', [DonationController::class, 'destroy'])->name('blood_bank_admin.donations.destroy');
        Route::get('blood-donation-events/{eventID}/health-details', [BloodDonationEventController::class, 'viewHealthDetailsByEvent'])->name('blood_bank_admin.health_details.index');
        Route::get('blood-donation-events/{eventID}/health-details/{healthDetailID}', [BloodDonationEventController::class, 'showHealthDetail'])->name('blood_bank_admin.health_details.show');
        Route::get('/events/{event}/health-details/search', [BloodDonationEventController::class, 'searchHealthDetails'])->name('blood_bank_admin.health_details.search');
        Route::get('appointments/{appointment_id}/health-details/{healthDetailID}', [BloodBankAdminAppointmentController::class, 'showTodayAppointmentHealthDetail'])->name('blood_bank_admin.health_details.show');
        Route::get('/blood-inventory', [InventoryController::class, 'index'])->name('blood_bank_admin.inventories.index');
        Route::put('/blood-inventory/{inventory}', [InventoryController::class, 'update'])->name('blood_bank_admin.inventories.update');
    });
});

// Authenticated routes
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments.index');
    Route::get('/appointments/create', [AppointmentController::class, 'create'])->name('appointments.create');
    Route::post('/get-available-time-slots', [AppointmentController::class, 'getAvailableTimeSlots'])->name('appointments.getAvailableTimeSlots');
    Route::get('/appointments/{appointment}', [AppointmentController::class, 'show'])->name('appointments.show');
    Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');
    Route::get('/appointments/{appointment}/edit', [AppointmentController::class, 'edit'])->name('appointments.edit');
    Route::put('/appointments/{appointment}', [AppointmentController::class, 'update'])->name('appointments.update');
    Route::delete('/appointments/{appointment}', [AppointmentController::class, 'destroy'])->name('appointments.destroy');
    Route::get('/events', [PublicEventController::class, 'index'])->name('events.index');
    Route::prefix('events')->group(function () {
        Route::get('/health-questionnaire/{eventID}', [PublicEventController::class, 'showHealthForm'])->name('events.health.form.show');
        Route::post('/health-questionnaire', [PublicEventController::class, 'storeHealthDetails'])->name('events.health.form.store');
    });
    
    Route::prefix('appointments')->group(function () {
        Route::get('/health-questionnaire', [AppointmentController::class, 'showHealthForm'])->name('appointments.health.form.show');
        Route::post('/health-questionnaire', [AppointmentController::class, 'storeHealthDetails'])->name('appointments.health.form.store');
    });

    Route::prefix('rewards')->group(function () {
        Route::get('/myrewards', [RewardController::class, 'history'])->name('rewards.myrewards');
        Route::get('/', [RewardController::class, 'index'])->name('rewards.index');
        Route::get('/{reward}', [RewardController::class, 'show'])->name('rewards.show');
        Route::post('/{reward}/confirm', [RewardController::class, 'confirm'])->name('rewards.confirm');
        Route::get('/redeem/{reward}', [RewardController::class, 'redeem'])->name('rewards.redeem');
        Route::post('/rewards/mark-as-used', [RewardController::class, 'markAsUsed'])->name('reward.markAsUsed');
    });

    Route::prefix('blood-requests')->group(function () {
        Route::get('/my-requests', [BloodRequestController::class, 'index'])->name('blood-requests.index'); // Show user's own requests
    });

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');

});
