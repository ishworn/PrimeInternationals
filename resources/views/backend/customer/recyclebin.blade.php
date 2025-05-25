@extends('admin.admin_master')
@section('admin')



<div class="page-content">


    @if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif

    <div class="row">
        <div class="col-12 mb-3">
            <a href="javascript:history.back()" class="btn btn-warning btn-rounded no-print"
                style="font-size: 15px; display: inline-flex; align-items: center; text-decoration: none; 
          background-color: #FFD700; color: black; padding: 10px 10px; border-radius: 5px; 
          margin-bottom: 15px; margin-top: 5px; margin-left: 20px;">
                <i class="fas fa-arrow-left" style="margin-right: 5px;"></i> Back
            </a>

        </div>
    </div>
    <div class="container-fluid">
        <!-- Start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0" style="font-size: 24px; font-weight: bold;"> Deleted Customers List</h4>
                </div>
                <a id="deleteButton"
                    class="btn btn-warning btn-rounded waves-effect waves-orange"
                    style="display:none; float:right; background-color: #B21807; color: white; 
          border: 2px solid #FFA500; transition: all 0.3s ease-in-out; 
          box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); margin-right:15px; margin-bottom:10px;">
                    <i class="fas fa-plus-circle"></i> Delete Selected
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
                                    <th><input type="checkbox" id="selectAllCheckbox"></th>
                                    <th>Sl</th>
                                    <th> Sender Name </th>
                                    <th>Receiver Name</th>
                                    <th>Country</th>
                                    <th>Dispatch To</th>
                                    <th>Dispatch Status</th>
                                    <th>Tracking Id</th>
                                    <th>Amount</th>

                                    <th class='payment-status'>Payment Status</th>
                                    <th style="width: 70px;">Restore</th>
                                </tr>
                            </thead>



                            <tbody>
                                @foreach($senders as $key => $sender)
                                <tr>
                                    <!-- <input type="checkbox" name="user_ids[]"> -->
                                    <td><input type="checkbox" class="checkboxes"></td>
                                    <td>{{ $key + 1 }}</td>
                                    <td> {{$sender->senderName}} </td>
                                    <td>{{ $sender->receiver->receiverName }}</td> <!-- Assuming receiverName field exists -->
                                    <td>{{ $sender->receiver->receiverCountry }}</td> <!-- Assuming country field exists -->
                                    <td>
                                        @php
                                        $dispatch = $sender->dispatch ?? null; // Fetch the dispatch related to the sender
                                        $dispatchBy = $dispatch ? $dispatch->dispatch_by : null;
                                        @endphp

                                        @if(!$dispatchBy)
                                        <span class="badge bg-secondary">N/A</span>
                                        @elseif($dispatchBy === 'Apex')
                                        <span class="badge bg-success" title="Apex">Apex</span>
                                        @elseif($dispatchBy === 'Dpnex')
                                        <span class="badge bg-warning" title="Dpnex">Dpnex</span>
                                        @elseif($dispatchBy === 'Pacific')
                                        <span class="badge bg-primary" title="Pacific">Pacific</span>
                                        @elseif($dispatchBy === 'Nepal Express')
                                        <span class="badge bg-info" title="Nepal Express">Nepal Express</span>
                                        @elseif($dispatchBy === 'DTDC')
                                        <span class="badge bg-danger" title="DTDC">DTDC</span>
                                        @elseif($dispatchBy === 'Aramax')
                                        <span class="badge bg-dark" title="Aramax">Aramax</span>
                                        @elseif($dispatchBy === 'Nepal Post')
                                        <span class="badge bg-light" title="Nepal Post">Nepal Post</span>
                                        @elseif($dispatchBy === 'SF International')
                                        <span class="badge bg-info" title="SF International">SF International</span>
                                        @else
                                        <span class="badge bg-warning" title="Unknown">Unknown</span>
                                        @endif
                                    </td>

                                    <td>
                                        @php
                                        $dispatch = $sender->dispatch ?? null; // Fetch the dispatch related to the sender
                                        $dispatchStatus = $dispatch ? $dispatch->status : null;
                                        @endphp

                                        @if(!$dispatchStatus)
                                        <span class="badge bg-danger">Pending</span>
                                        @elseif($dispatchStatus === 'pending')
                                        <span class="badge bg-danger" title="Not Sent">Pending</span>
                                        @elseif($dispatchStatus === 'dispatch')
                                        <span class="badge bg-success" title="Sent">Dispatch</span>
                                        @else
                                        <span class="badge bg-warning">Unknown Status</span>
                                        @endif
                                    </td>
                                    <td>{{ $sender->trackingId ?? 'N/A' }}</td>
                                    <td>{{ $sender->payments->total_paid ?? 'N/A' }}</td> <!-- Assuming amount field exists -->

                                    <td class='payment-status'>
                                        @php
                                        $paymentStatus = optional($sender->payments)->status ?? 'pending';
                                        @endphp

                                        @if($paymentStatus === 'pending')
                                        <span class="badge bg-danger">Pending</span>
                                        @elseif($paymentStatus === 'partial')
                                        <span class="badge bg-warning">Partial</span>
                                        @elseif($paymentStatus === 'completed')
                                        <span class="badge bg-success">Completed</span>
                                        @else
                                        <span class="badge bg-secondary">Unknown</span>
                                        @endif
                                    </td>



                                    <!-- Actions (unchanged) -->
                                    <td class="text-center">
                                        <a href="{{ route('customer.restore', $sender->id)}}"
                                            class="bg-green-700 text-white px-2 py-1 rounded hover:bg-green-600">
                                            Restore
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.tailwindcss.com"></script>

<!-- for select all -->
<script>
    $('#selectAllCheckbox').change(function() {
        $('.checkboxes').prop('checked', $(this).prop('checked'));
    });

    function updateDeleteButtonVisibility() {
        if ($('.checkboxes:checked').length > 0) {
            $('#deleteButton').show();
        } else {
            $('#deleteButton').hide();
        }
    }

    // Handle individual checkboxes
    $(document).on('change', '.checkboxes', function() {
        updateDeleteButtonVisibility();
    });

    // Handle Select All
    $('#selectAllCheckbox').change(function() {
        $('.checkboxes').prop('checked', $(this).prop('checked'));
        updateDeleteButtonVisibility();
    });
</script>
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

    }

    table th,
    table td {
        text-align: center;

        font-size: 14px;
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

    .payment-method,
    .payment-status {


        width: 100px;
        /* Set a specific width for the columns */

    }
</style>




@endsection