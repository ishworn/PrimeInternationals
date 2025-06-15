@extends('admin.admin_master')
@section('admin')


@if(auth()->user()->hasRole('vendor'))


{{-- Vendor specific dashboard --}}
<div class="content p-4 max-w-7xl mx-auto">
        <h2 class="mb-2 text-3xl font-bold text-yellow-500 pb-3 uppercase tracking-wide">
            Vendor Dashboard
        </h2>

    <h4 class="mb-3 text-gray-700 text-lg">Welcome, <span class="font-semibold">{{ auth()->user()->name }}</span>. Here's an overview of your vendor stats.</h4>
    <div class="flex flex-row gap-6 p-2">
        <div class="container-fluid px-4">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row mb-3">
                        <!-- Total Orders Card -->
                        <div class="col-md-4">
                            <div class="flex border transition transform duration-300 hover:-translate-y-1 hover:shadow-lg cursor-pointer">

                                <div class="bg-warning text-light p-4">
                                    <div class="d-flex align-items-center h-100">
                                        <i class="fas fa-border-none fa-3x "></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 bg-gray-200 p-4">
                                    <p class="text-uppercase text-secondary mb-0">Total Orders</p>
                                    <h3 class="font-weight-bold mb-0">100</h3>
                                </div>
                            </div>
                        </div>

                        <!-- Pending Shipments Card -->
                        <div class="col-md-4 ">
                            <div class="flex border transition transform duration-300 hover:-translate-y-1 hover:shadow-lg cursor-pointer">

                                <div class="bg-black text-light p-4">
                                    <div class="d-flex align-items-center h-100">
                                        <i class="fa fa-3x fa-fw fa-truck"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 bg-gray-200 p-4">
                                    <p class="text-uppercase text-secondary mb-0">Pending Shipments</p>
                                    <h3 class="font-weight-bold mb-0">50</h3>
                                </div>
                            </div>
                        </div>

                        <!-- Total Shipments Card -->
                        <div class="col-md-4 ">
                            <div class="flex border transition transform duration-300 hover:-translate-y-1 hover:shadow-lg cursor-pointer">

                                <div class="bg-success text-light p-4">
                                    <div class="d-flex align-items-center h-100">
                                        <i class="fas fa-dollar-sign fa-3x"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 bg-gray-200 p-4">
                                    <p class="text-uppercase text-secondary mb-0">Total Revenue</p>
                                    <h3 class="font-weight-bold mb-0">125</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            {{-- Additional info section --}}

            <div class="bg-white shadow-md rounded-lg p-6 mt-8">
                <h3 class="text-xl font-semibold mb-4 border-b pb-2 text-gray-800">Recent Activities</h3>

                <ul class="space-y-4 text-gray-700">

                    <!-- Activity 1 -->
                    <li class="flex items-start space-x-3">
                        <div class="text-blue-500">
                            <i class="fas fa-shopping-cart text-lg"></i>
                        </div>
                        <div>
                            <p class="font-medium">New Order</p>
                            <p class="text-sm text-gray-500">#{{ $latestOrderNumber ?? 'N/A' }} placed on {{ $latestOrderDate ?? 'N/A' }}</p>
                        </div>
                    </li>

                    <!-- Activity 2 -->
                    <li class="flex items-start space-x-3">
                        <div class="text-green-500">
                            <i class="fas fa-truck text-lg"></i>
                        </div>
                        <div>
                            <p class="font-medium">Shipment Dispatched</p>
                            <p class="text-sm text-gray-500">#{{ $latestShipmentNumber ?? 'N/A' }}</p>
                        </div>
                    </li>

                    <!-- Activity 3 -->
                    <li class="flex items-start space-x-3">
                        <div class="text-yellow-500">
                            <i class="fas fa-boxes text-lg"></i>
                        </div>
                        <div>
                            <p class="font-medium">Inventory Updated</p>
                            <p class="text-sm text-gray-500">Stock adjustments completed</p>
                        </div>
                    </li>
                </ul>
            </div>
            <


            @else


            <div class="content p-4 mb-10">
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

                            <!-- Total Shipments Card -->
                            <div class="col-md-4 ">
                                <div class="d-flex border animated-card">
                                    <div class="bg-custom-background text-light p-4">
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
                            <div class="col-md-4 ">
                                <div class="d-flex border animated-card">
                                    <div class="bg-custom-background text-light p-4">
                                        <div class="d-flex align-items-center h-100">
                                            <i class="fa fa-3x fa-fw fa-truck"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 bg-white p-4">
                                        <p class="text-uppercase text-secondary mb-0">Pending Deliveries</p>
                                        <h3 class="font-weight-bold mb-0">{{$pendingDispatch}}</h3>
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
                        Agencies WIse Weight Statistics
                    </div>
                    <div class="card-body mt-4">
                        <canvas id="barChart" style="width:100%;max-width:100%;height: 300px;"></canvas>
                    </div>
                </div>


                <div class="grid grid-cols-2 gap-4">
                    <div>

                        <!-- Doughnut graph -->
                        <canvas id="myChart" style="width:100%;max-width:600px"></canvas>
                    </div>
                    <div>

                        <!-- pie chart -->
                        <canvas id="pieChart" style="width:100%;max-width:600px"></canvas>
                    </div>

                </div>

            </div>


            <script src="https://cdn.tailwindcss.com"></script>
            <!-- Include Chart.js -->
            <!-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> -->
            <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>

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
                document.addEventListener('DOMContentLoaded', function() {
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

            <!-- doughnut graph script -->
            <script>
                var xValues = @json($chartLabels);
                var yValues = @json($chartValues);
                var barColors = [
                    "#22bb33",
                    "#f0ad4e",
                    "#bb2124",



                ];

                new Chart("myChart", {
                    type: "doughnut",
                    data: {
                        labels: xValues,
                        datasets: [{
                            backgroundColor: barColors,
                            data: yValues,

                        }]
                    },
                    options: {
                        title: {
                            display: true,
                            text: "Payment Status",

                        }
                    }
                });
            </script>

            <!-- bar graph  -->
            <script>
                var xValues = @json($barchartLabels);
                var yValues = @json($barchartValues);
                var barColors = ["red", "green", "blue", "orange", "brown", "red", "green", "blue", "orange", "brown"];

                new Chart("barChart", {
                    type: "bar",
                    data: {
                        labels: xValues,
                        datasets: [{
                            backgroundColor: barColors,
                            data: yValues
                        }]
                    },
                    options: {
                        legend: {
                            display: false
                        },
                        title: {
                            display: true,
                            text: "Agencies WIse Weight Statistics"
                        }
                    }
                });
            </script>

            <!-- pie chart -->
            <script>
                var xValues = @json($pichartLabels);
                var yValues = @json($pichartValues);
                var barColors = [
                    "#b91d47",
                    "#00aba9",
                    "#2b5797",
                    "#e8c3b9",
                    "#1e7145"
                ];

                new Chart("pieChart", {
                    type: "pie",
                    data: {
                        labels: xValues,
                        datasets: [{
                            backgroundColor: barColors,
                            data: yValues
                        }]
                    },
                    options: {
                        title: {
                            display: true,
                            text: "Agencies WIse Parcel Statistics"
                        }
                    }
                });
            </script>

            @endif
            <script src="https://cdn.tailwindcss.com"></script>

            @endsection