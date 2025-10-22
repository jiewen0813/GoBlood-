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
        <!-- Daily Blood Collection -->
        <div class="col-md-6 mb-4">
            <div class="card shadow">
                <div class="card-body">
                    <h5 class="card-title">Daily Blood Collection</h5>
                    <canvas id="dailyChart" width="400" height="200"></canvas>
                    <button id="exportDailyChart" class="btn btn-primary mt-3">Download Daily Graph</button>
                </div>
            </div>
        </div>

        <!-- Monthly Blood Collection -->
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

    <!-- Blood Collected by Blood Type per Month -->
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-body">
                    <h5 class="card-title">Blood Collected by Blood Type per Month</h5>
                    <canvas id="bloodTypeMonthlyChart"></canvas>
                    <button id="exportBloodTypeChart" class="btn btn-primary mt-3">Download Blood Type Graph</button>
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
                title: { display: true, text: 'Blood Collected in Last 7 Days' }
            },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 } }
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
                title: { display: true, text: 'Blood Collected in Last 12 Months' }
            },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 } }
            }
        }
    });

    // Blood Type Monthly Data for Stacked Bar Chart
    const bloodTypeMonthlyData = @json($bloodTypeMonthlyData);

    // Extract unique months and blood types
    const months = [...new Set(bloodTypeMonthlyData.map(item => item.month))];
    const bloodTypes = [...new Set(bloodTypeMonthlyData.map(item => item.blood_type))];

    // Prepare dataset for stacked chart
    const datasets = bloodTypes.map(type => {
        return {
            label: type,
            data: months.map(month => {
                const entry = bloodTypeMonthlyData.find(item => item.month === month && item.blood_type === type);
                return entry ? entry.total_collected : 0;
            }),
            backgroundColor: getRandomColor()
        };
    });

    // Get the canvas context
    const bloodTypeMonthlyCtx = document.getElementById("bloodTypeMonthlyChart").getContext("2d");
    // Stacked Bar Chart for Blood Type Per Month
    const bloodTypeMonthlyChart = new Chart(bloodTypeMonthlyCtx, {
        type: "bar",
        data: {
            labels: months.map(month => new Date(month + "-01").toLocaleString('default', { month: 'short', year: 'numeric' })),
            datasets: datasets
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: "top" },
                title: { display: true, text: "Blood Collected by Blood Type per Month" }
            },
            scales: {
                x: { stacked: true },
                y: { stacked: true, beginAtZero: true }
            }
        }
    });

    // Random Color Generator
    function getRandomColor() {
        return `rgba(${Math.floor(Math.random() * 255)}, ${Math.floor(Math.random() * 255)}, ${Math.floor(Math.random() * 255)}, 0.6)`;
    }

    // Export Graphs as Image
    document.getElementById('exportDailyChart').addEventListener('click', function () {
        exportChart(dailyChart, 'daily_blood_collection_graph.png');
    });

    document.getElementById('exportMonthlyChart').addEventListener('click', function () {
        exportChart(monthlyChart, 'monthly_blood_collection_graph.png');
    });

    document.getElementById('exportBloodTypeChart').addEventListener('click', function () {
        exportChart(bloodTypeMonthlyChart, 'blood_collected_by_type_per_month.png');
    });

    function exportChart(chart, filename) {
        const link = document.createElement('a');
        link.href = chart.toBase64Image();
        link.download = filename;
        link.click();
    }
</script>
@endsection
