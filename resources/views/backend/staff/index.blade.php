@extends('admin.admin_master')

@section('admin')



<div class="page-content">
    <div class="container-fluid">
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
                                <h3 class="font-weight-bold mb-0">{{ $totalStaffs }}</h3>
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
                                <p class="text-uppercase text-secondary mb-0">Total Departments</p>
                                <h3 class="font-weight-bold mb-0">{{$totalDepartments}}</h3>
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
                                <p class="text-uppercase text-secondary mb-0">Total Salary Spent</p>
                                <h3 class="font-weight-bold mb-0">{{$totalSalary}}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="row pb-20">
            <div class="col-12">

                <div class="card shadow-lg">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="dropdown mb-4 float-end">
                                <button class="btn btn-info dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Staff Salary Countdown
                                </button>
                                <ul class="dropdown-menu p-3" style="width: 320px; max-height: 400px; overflow-y: auto;">
                                    @foreach($staffs as $staff)
                                    @php
                                    $status = $salaryStatus;
                                    $colorClass = 'text-muted'; // default
                                    

                                    if ($status) {
                                    if (strpos($status, 'days left') !== false || $status === 'Today') {
                                    $colorClass = 'text-success'; // green for upcoming or today
                                    } elseif (strpos($status, 'days ago') !== false) {
                                    $colorClass = 'text-danger'; // red for past
                                    }
                                    }
                                    @endphp
                                    <li class="mb-3">
                                        <strong>{{ $staff->staffName }}</strong><br>
                                        Join Date: {{ \Carbon\Carbon::parse($staff->join_date)->format('d M Y') }}<br>
                                        Salary: Rs. {{ number_format($staff->staffSalary, 2) }}<br>
                                        @if($status)
                                        <small class="{{ $colorClass }}">Next Salary: {{ $status }}</small>
                                        @else
                                        <small class="text-muted">Next Salary: --</small>
                                        @endif
                                    </li>
                                    @if (!$loop->last)
                                    <hr class="my-1">
                                    @endif
                                    @endforeach
                                </ul>
                            </div>



                            <h4 class="mb-0" style="font-size: 24px; font-weight: bold;">Staff List</h4>
                            <a href="{{ route('staffs.create') }}" class="btn bg-yellow-400 hover:bg-yellow-500 text-black font-semibold py-2 px-4 rounded">
                                + Add Staff
                            </a>


                        </div>


                        <table id="datatable" class="table table-bordered dt-responsive nowrap" style="width: 100%;">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th>SL</th>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th>Address</th>
                                    <th>Department</th>
                                    <th>Join Date</th>
                                    <th>Salary</th>
                                    <th>File</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($staffs as $key => $staff)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $staff->staffName }}</td>
                                    <td>{{ $staff->staffPhone }}</td>
                                    <td>{{ $staff->staffEmail }}</td>
                                    <td>{{ $staff->staff_Address }}</td>
                                    <td>{{$staff->department}}</td>
                                    <td>{{$staff->join_date}}</td>
                                    <td>{{ $staff->staffSalary }}</td>
                                    <td>
                                        @foreach ($staff->documents as $doc)
                                        <a href="{{ asset('storage/' . $doc->filepath) }}" target="_blank">
                                            {{ $doc->type }}
                                        </a><br>
                                        @endforeach
                                    </td>



                                    <td>
                                        <a href="{{ route('staffs.edit', $staff->id) }}" class="btn btn-info btn-sm mr-2" title="Edit">
                                            <i class="fa-regular fa-pen-to-square"></i>
                                        </a>



                                        <form action="{{ route('staffs.delete', $staff->id) }}" method="POST" style="display: inline-block;" title="Delete">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>


                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <a href="{{ url('/staffs/export-csv') }}"
                            style="padding: 10px 20px; background: #28a745; color: white; text-decoration: none; border-radius: 5px;">
                            Download CSV
                        </a>
                    </div> <!-- card-body -->
                </div> <!-- card -->
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.tailwindcss.com"></script>
<!-- Font Awesome 6.0.0 (or latest) CDN -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">


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



@endsection