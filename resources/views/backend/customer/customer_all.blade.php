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
                    <h4 class="mb-sm-0" style="font-size: 24px; font-weight: bold;">Customers List</h4>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 mb-3">
                <a href="{{ route('customer.add') }}" class="btn btn-warning btn-rounded waves-effect waves-orange"
                    style="float:right; padding: 12px 20px; background-color: #FFA500; color: #555; border: 2px solid #FFA500; 
                          transition: all 0.3s ease-in-out; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                    <i class="fas fa-plus-circle"></i> Add Customer
                </a>
            </div>
        </div>
        <!-- End page title -->

        <div class="row">
            <div class="col-12">
                <div class="card shadow-lg">
                    <div class="card-body">
                        <h4 class="card-title mb-4" style="font-size: 22px; font-weight: bold;"></h4>

                        <table id="datatable" class="table table-bordered dt-responsive nowrap" style="width: 100%;">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th>Sl</th>
                                    <th>Sender Name</th>
                                    <th>Receiver Name</th>
                                    <th>Country</th>
                                    <th>Tracking Id</th>
                                    <th>Amount</th>
                                    <th>Payment Method</th>
                                    <th>Payment Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>



                            <tbody>
    @foreach($senders as $key => $sender)
    <tr>
        <td>{{ $key + 1 }}</td>
        <td>{{ $sender->senderName }}</td>
        <td>{{ $sender->receiver->receiverName }}</td> <!-- Assuming receiverName field exists -->
        <td>{{ $sender->receiver->receiverName }}</td> <!-- Assuming country field exists -->
        <td>{{ $sender->trackingId ?? 'N/A' }}</td>
        <td>{{ $sender->payments->amount ?? 'N/A' }}</td> <!-- Assuming amount field exists -->
        <td>
    @php
    $payments = $sender->payments ?? collect();
    $paymentMethods = $payments->pluck('payment_method')->filter()->unique();        
        $hasCash = $paymentMethods->contains('Cash');
        $hasBankTransfer = $paymentMethods->contains('Bank Transfer');
        $hasBoth = $paymentMethods->contains('Both');
    @endphp

    @if($paymentMethods->isEmpty())
        <span class="text-muted">N/A</span>
    @else
        @if($hasBoth)
            <span class="badge bg-warning" title="Both Cash and Bank Transfer">Both</span>
        @elseif($hasCash)
            <span class="badge bg-success" title="Cash">Cash</span>
        @elseif($hasBankTransfer)
            <span class="badge bg-primary" title="Bank Transfer">Bank Transfer</span>
        @endif
    @endif
</td>
<td>
    @php
  
    $payments = $sender->payments ?? collect();
        $paymentStatuses = $payments->pluck('status')->filter()->unique(); 
        $hasunpaid = $paymentStatuses->contains('unpaid');  
        $haspaid = $paymentStatuses->contains('paid');
    @endphp

    @if($paymentStatuses->isEmpty())
        <span class="badge bg-danger">Unpaid</span>
    @else
        @if($hasunpaid)
            <span class="badge bg-danger" title="Both Cash and Bank Transfer">Unpaid</span>
        @elseif($haspaid)
            <span class="badge bg-success" title="Cash">Paid</span>
      
        
        @endif
    @endif
</td>

        <!-- Actions (unchanged) -->
        <td class="d-flex justify-content-center">
            <a href="{{ route('customer.edit', $sender->id) }}" class="btn btn-info btn-sm mx-1" title="Edit Data">
                <i class="fas fa-edit"></i>
            </a>
            <a href="{{ route('customer.delete', $sender->id) }}" class="btn btn-danger btn-sm mx-1" title="Delete Data" id="delete">
                <i class="fas fa-trash-alt"></i>
            </a>
            <a href="{{ route('customer.preview', $sender->id) }}" class="btn btn-dark btn-sm mx-1" title="Preview">
                <i class="fas fa-eye"></i>
            </a>
            <a href="{{ route('customer.addweight', $sender->id) }}" class="btn btn-warning btn-sm mx-1" title="Preview">
                <i class="fas fa-weight"></i>
            </a>
        </td>
    </tr>
    @endforeach
</tbody>

<!-- JavaScript -->



                            <!-- JavaScript -->



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

    table th,
    table td {
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
</style>
<script>
    // Update Payment Method and change button color based on selection
    function updatePaymentMethod(element, key) {
        var button = document.getElementById('paymentMethodDropdown' + key);
        button.textContent = element.textContent;

        // Change button color based on selection
        if (element.textContent === 'Credit Card' || element.textContent === 'PayPal') {
            button.classList.remove('btn-secondary', 'btn-success', 'btn-warning');
            button.classList.add('btn-primary'); // Default for Credit Card or PayPal
        } else if (element.textContent === 'Bank Transfer') {
            button.classList.remove('btn-secondary', 'btn-primary', 'btn-warning');
            button.classList.add('btn-success'); // Green for Bank Transfer
        } else if (element.textContent === 'Cash') {
            button.classList.remove('btn-secondary', 'btn-primary', 'btn-success');
            button.classList.add('btn-warning'); // Yellow for Cash
        }
    }

    // Update Payment Status and change button color based on selection
    function updatePaymentStatus(element, key, color) {
        var button = document.getElementById('paymentStatusDropdown' + key);
        button.textContent = element.textContent;

        // Change button color based on selected status
        button.classList.remove('btn-secondary', 'btn-success', 'btn-danger', 'btn-warning');
        if (color === 'green') {
            button.classList.add('btn-success'); // Green for Paid
        } else if (color === 'red') {
            button.classList.add('btn-danger'); // Red for Unpaid
        } else if (color === 'orange') {
            button.classList.add('btn-warning'); // Orange for Pending
        }
    }
</script>



@endsection