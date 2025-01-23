<?php

use App\Admin\Controllers\UserController;
use App\Admin\Controllers\DonationController;
use App\Admin\Controllers\InventoryController;
use App\Admin\Controllers\BloodDonationEventController;
use App\Admin\Controllers\EducationController;
use App\Admin\Controllers\AppointmentController;
use App\Admin\Controllers\RewardController;
use App\Admin\Controllers\BloodRequestController;
use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('home');
    $router->resource('users', UserController::class);
    $router->resource('donations', DonationController::class);
    $router->resource('inventory', InventoryController::class);
    $router->resource('blood-bank-admins', BloodBankAdminController::class);
    $router->resource('blood-donation-events', BloodDonationEventController::class);
    $router->resource('educations', EducationController::class);
    $router->resource('appointments', AppointmentController::class);
    $router->resource('rewards', RewardController::class);
    $router->resource('redemptions', RedemptionController::class);
    $router->resource('blood-requests', BloodRequestController::class);
});
