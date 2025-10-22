<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BloodBankAdminDashboardController extends Controller
{
    public function index()
    {
        // Fetch all users
        $users = User::all();

        // Blood collected per day (last 7 days)
        $dailyData = Donation::where('blood_bank_id', auth()->guard('blood_bank_admin')->id())
            ->where('date_donated', '>=', now()->subDays(7))
            ->selectRaw('DATE(date_donated) as date, COUNT(id) as total_quantity')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Blood collected per month (last 12 months)
        $monthlyData = Donation::where('blood_bank_id', auth()->guard('blood_bank_admin')->id())
            ->where('date_donated', '>=', now()->subYear())
            ->selectRaw('DATE_FORMAT(date_donated, "%Y-%m") as month, COUNT(id) as total_quantity')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Blood collected by blood type per month (Stacked Bar Chart)
        $bloodTypeMonthlyData = Donation::join('users', 'donations.user_id', '=', 'users.id')
            ->where('donations.blood_bank_id', auth()->guard('blood_bank_admin')->id())
            ->where('donations.date_donated', '>=', now()->subYear())
            ->selectRaw('DATE_FORMAT(donations.date_donated, "%Y-%m") as month, users.blood_type, COUNT(donations.id) as total_collected')
            ->groupBy('month', 'users.blood_type')
            ->orderBy('month')
            ->get();

        return view('blood_bank_admin.dashboard', [
            'users' => $users,
            'dailyData' => $dailyData,
            'monthlyData' => $monthlyData,
            'bloodTypeMonthlyData' => $bloodTypeMonthlyData, // Pass data for blood type chart
        ]);
    }
}
