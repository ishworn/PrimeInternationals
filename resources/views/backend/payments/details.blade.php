@extends('admin.admin_master')

@section('admin')

<div class="page-content">
    @if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif
    <a href="javascript:history.back()" class="btn btn-warning btn-rounded no-print"
        style="font-size: 15px; display: inline-flex; align-items: center; text-decoration: none; 
          background-color: #FFD700; color: black; padding: 10px 10px; border-radius: 5px; 
          margin-bottom: 15px; margin-top: 5px; margin-left: 20px;">
        <i class="fas fa-arrow-left" style="margin-right: 5px;"></i> Back
    </a>
    <div class="container-fluid">
        <!-- Start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0" style="font-size: 24px; font-weight: bold;">Payment Details</h4>
                </div>
            </div>
        </div>
        <!-- End page title -->

        <!-- Trackings Table -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-lg">
                    <div class="card-body">
                        <h4 class="card-title mb-4" style="font-size: 22px; font-weight: bold;">Today's Income Summary</h4>

                        <!-- Table to Display Today's Income -->
                        <table class="table table-bordered">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th>Payment Method</th>
                                    <th>Total Income</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $totalCash = 0;
                                    $totalBankTransfer = 0;
                                @endphp

                                <!-- Loop through payments and calculate totals -->
                                @foreach($payments as $payment)
                                    @if($payment->payment_method === 'Cash')
                                        @php $totalCash += $payment->amount; @endphp
                                    @elseif($payment->payment_method === 'Bank Transfer')
                                        @php $totalBankTransfer += $payment->amount; @endphp
                                    @endif
                                @endforeach

                                <!-- Display Cash Total -->
                                <tr>
                                    <td>Cash</td>
                                    <td>{{ number_format($totalCash, 2) }}</td>
                                </tr>

                                <!-- Display Bank Transfer Total -->
                                <tr>
                                    <td>Bank Transfer</td>
                                    <td>{{ number_format($totalBankTransfer, 2) }}</td>
                                </tr>

                                <!-- Display Grand Total -->
                                <tr class="bg-light">
                                    <td><strong>Grand Total</strong></td>
                                    <td><strong>{{ number_format($totalCash + $totalBankTransfer, 2) }}</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div> <!-- End col -->
        </div> <!-- End row -->
    </div> <!-- container-fluid -->
</div>

<!-- Add some custom styles for modern design -->
<style>
    .btn {
        transition: all 0.3s ease;
    }
    
    .btn:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
    }

    .card {
        border-radius: 8px;
        overflow: hidden;
    }

    table th, table td {
        text-align: center;
        padding: 12px;
        font-size: 16px;
    }

    table tbody tr:hover {
        background-color: #f1f1f1;
    }

    table th {
        background-color: #3e8e41;
        color: white;
        text-align: center;
    }

    table td {
        vertical-align: middle;
    }

    .bg-light {
        background-color: #f8f9fa;
    }
</style>

@endsection