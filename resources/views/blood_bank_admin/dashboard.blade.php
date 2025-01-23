@extends('layouts.admin')

@section('title', 'GoBlood | Admin Dashboard')

@section('header')
    <h2 class="font-semibold text-xl text-black leading-tight">
        {{ __('Blood Bank Admin Dashboard') }}
    </h2>
@endsection

@section('content')
<div class="container mt-4">
    <!-- Blood Collection Graphs -->
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card shadow">
                <div class="card-body">
                    <h5 class="card-title">Daily Blood Collection</h5>
                    <canvas id="dailyChart" width="400" height="200"></canvas>
                    <button id="exportDailyChart" class="btn btn-primary mt-3">Download Daily Graph</button>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card shadow">
                <div class="card-body">
                    <h5 class="card-title">Monthly Blood Collection</h5>
                    <canvas id="monthlyChart" width="400" height="200"></canvas>
                    <button id="exportMonthlyChart" class="btn btn-primary mt-3">Download Monthly Graph</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Daily Data
    const dailyLabels = {!! json_encode($dailyData->pluck('date')->map(fn($date) => \Carbon\Carbon::parse($date)->format('d-m-Y'))) !!};
    const dailyQuantities = {!! json_encode($dailyData->pluck('total_quantity')) !!};

    const dailyCtx = document.getElementById('dailyChart').getContext('2d');
    const dailyChart = new Chart(dailyCtx, {
        type: 'line',
        data: {
            labels: dailyLabels,
            datasets: [{
                label: 'Blood Collected (Units)',
                data: dailyQuantities,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 2,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Blood Collected in Last 7 Days'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1 // Whole numbers
                    }
                }
            }
        }
    });

    // Monthly Data
    const monthlyLabels = {!! json_encode($monthlyData->pluck('month')->map(fn($month) => \Carbon\Carbon::parse($month . "-01")->format('M Y'))) !!};
    const monthlyQuantities = {!! json_encode($monthlyData->pluck('total_quantity')) !!};

    const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
    const monthlyChart = new Chart(monthlyCtx, {
        type: 'line',
        data: {
            labels: monthlyLabels,
            datasets: [{
                label: 'Blood Collected (Units)',
                data: monthlyQuantities,
                backgroundColor: 'rgba(153, 102, 255, 0.2)',
                borderColor: 'rgba(153, 102, 255, 1)',
                borderWidth: 2,
                tension: 0.6
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Blood Collected in Last 12 Months'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1 // Whole numbers
                    }
                }
            }
        }
    });

    // Export Daily Graph as Image
    document.getElementById('exportDailyChart').addEventListener('click', function () {
        const link = document.createElement('a');
        link.href = dailyChart.toBase64Image();
        link.download = 'daily_blood_collection_graph.png';
        link.click();
    });

    // Export Monthly Graph as Image
    document.getElementById('exportMonthlyChart').addEventListener('click', function () {
        const link = document.createElement('a');
        link.href = monthlyChart.toBase64Image();
        link.download = 'monthly_blood_collection_graph.png';
        link.click();
    });
</script>
@endsection
