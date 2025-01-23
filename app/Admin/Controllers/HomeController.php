<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Donation; // Import your BloodDonation model
use App\Models\User; // Import User model
use App\Models\BloodBankAdmin; // Import BloodBank model
use Carbon\Carbon; // To work with date ranges
use OpenAdmin\Admin\Admin;
use OpenAdmin\Admin\Controllers\Dashboard;
use OpenAdmin\Admin\Layout\Column;
use OpenAdmin\Admin\Layout\Content;
use OpenAdmin\Admin\Layout\Row;

class HomeController extends Controller
{
    public function index(Content $content)
    {
        // Get data for the current week
        $startOfWeek = Carbon::now()->startOfWeek(); // Monday
        $endOfWeek = Carbon::now()->endOfWeek(); // Sunday

        $bloodCollected = Donation::selectRaw('DATE(created_at) as date, SUM(quantity) as total')
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->groupBy('date')
            ->pluck('total', 'date');

        // Prepare data for the chart
        $dates = [];
        $totals = [];
        for ($date = $startOfWeek; $date <= $endOfWeek; $date->addDay()) {
            $dates[] = $date->format('Y-m-d');
            $totals[] = $bloodCollected->get($date->format('Y-m-d'), 0); // Default to 0 if no data
        }

        return $content
        ->css_file(Admin::asset("https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css")) // Include Font Awesome
        ->css_file(Admin::asset("open-admin/css/pages/dashboard.css"))
        ->title('Dashboard')
        ->description('Overview of Blood Donations')
        ->row(function (Row $row) use ($dates, $totals) {

            // Add graph with export functionality
            $row->column(12, function (Column $column) use ($dates, $totals) {
                $chartData = [
                    'labels' => $dates,
                    'datasets' => [
                        [
                            'label' => 'Blood Collected (Units)',
                            'backgroundColor' => '#FF6384',
                            'data' => $totals,
                        ],
                    ],
                ];
                $column->append("
                    <div style='text-align: center;'>
                        <canvas id='bloodCollectionChart' height='100'></canvas>
                        <button id='downloadGraph' class='btn btn-primary' style='margin-top: 10px;'>
                            <i class='fas fa-download'></i> Download Graph
                        </button>
                    </div>
                    <script src='https://cdn.jsdelivr.net/npm/chart.js'></script>
                    <script>
                        var ctx = document.getElementById('bloodCollectionChart').getContext('2d');
                        var chart = new Chart(ctx, {
                            type: 'line',
                            data: " . json_encode($chartData) . ",
                            options: {
                                responsive: true,
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        stepSize: 1,
                                        title: {
                                            display: true,
                                            text: 'Units Collected'
                                        }
                                    },
                                    x: {
                                        title: {
                                            display: true,
                                            text: 'Date'
                                        }
                                    }
                                },
                                plugins: {
                                    legend: {
                                        display: true,
                                        position: 'top',
                                    }
                                }
                            }
                        });

                        // Export graph functionality
                        document.getElementById('downloadGraph').addEventListener('click', function () {
                            var link = document.createElement('a');
                            link.download = 'blood_collection_graph.png'; // File name
                            link.href = document.getElementById('bloodCollectionChart').toDataURL('image/png'); // Convert canvas to image
                            link.click();
                        });
                    </script>");
            });
        });

    }
}
