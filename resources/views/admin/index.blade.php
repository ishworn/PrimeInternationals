@extends('admin.admin_master')
@section('admin')

<div class="content p-4">
    <h2 class="mb-4 border-bottom pb-3 text-uppercase">Dashboard</h2>

    <!-- Animated Cards -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row mb-4">
                <!-- Total Branch Card -->
                <div class="col-md-4">
                    <div class="d-flex border animated-card">
                        <div class="bg-custom-background text-light p-4">
                            <div class="d-flex align-items-center h-100">
                                <i class="fa fa-3x fa-fw fa-code-branch"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 bg-white p-4">
                            <p class="text-uppercase text-secondary mb-0">Total Staff</p>
                            <h3 class="font-weight-bold mb-0">{{ $totalUser }}</h3>
                        </div>
                    </div>
                </div>

                <!-- Total Manager Card -->
                <div class="col-md-4">
                    <div class="d-flex border animated-card">
                        <div class="bg-custom-background text-light p-4">
                            <div class="d-flex align-items-center h-100">
                                <i class="fa fa-3x fa-fw fa-users"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 bg-white p-4">
                            <p class="text-uppercase text-secondary mb-0">Total Manager</p>
                            <h3 class="font-weight-bold mb-0">15</h3>
                        </div>
                    </div>
                </div>

                <!-- Total Company Income Card -->
                <div class="col-md-4">
                    <div class="d-flex border animated-card">
                        <div class="bg-custom-background text-light p-4">
                            <div class="d-flex align-items-center h-100">
                                <i class="fa fa-3x fa-fw fa-money-bill-alt"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 bg-white p-4">
                            <p class="text-uppercase text-secondary mb-0">Total Company Income</p>
                            <h3 class="font-weight-bold mb-0">{{ number_format($totalIncome, 2) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Income Statistics Chart -->
    <div class="card mb-4">
        <div class="card-header font-weight-bold">
            <i class="fa fa-chart-line"></i>
            MONTHLY INCOME STATISTICS
        </div>
        <div class="card-body mt-4">
            <canvas id="monthlyIncomeChart" style="height: 250px;"></canvas>
        </div>
    </div>

    <!-- Additional Cards -->
    <div class="row mb-5">
    <!-- Total Shipments Card -->
    <div class="col-md-6 col-12">
        <div class="d-flex border animated-card">
            <div class="bg-yellow-orange text-light p-4">
                <div class="d-flex align-items-center h-100">
                    <i class="fa fa-3x fa-fw fa-box"></i>
                </div>
            </div>
            <div class="flex-grow-1 bg-white p-4">
                <p class="text-uppercase text-secondary mb-0">Total Shipments</p>
                <h3 class="font-weight-bold mb-0">{{$totalCustomer}}</h3>
            </div>
        </div>
    </div>

    <!-- Pending Deliveries Card -->
    <div class="col-md-6 col-12">
        <div class="d-flex border animated-card">
            <div class="bg-yellow-orange text-light p-4">
                <div class="d-flex align-items-center h-100">
                    <i class="fa fa-3x fa-fw fa-truck"></i>
                </div>
            </div>
            <div class="flex-grow-1 bg-white p-4">
                <p class="text-uppercase text-secondary mb-0">Pending Deliveries</p>
                <h3 class="font-weight-bold mb-0">85</h3>
            </div>
        </div>
    </div>
</div>

</div>

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Custom CSS for Animations -->
<style>
    .animated-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .animated-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
    }

    .bg-custom-background {
        background: linear-gradient(45deg, #FFA500, #FFD700);
    }

    .bg-yellow-orange {
        background: linear-gradient(45deg, #FFB74D, #FF9800);
    }
</style>

<!-- Chart Script -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var ctx = document.getElementById('monthlyIncomeChart').getContext('2d');
        var monthlyIncomeChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Monthly Income',
                    data: [5000, 7000, 10000, 12000, 15000, 18000, 20000, 22000, 25000, 27000, 30000, 35000],
                    backgroundColor: 'rgba(255, 165, 0, 0.5)',
                    borderColor: '#FF9800',
                    borderWidth: 2,
                    pointBackgroundColor: '#FFA500',
                    pointRadius: 5,
                    pointHoverRadius: 7,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)',
                        }
                    },
                    x: {
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)',
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
    });
</script>

@endsection