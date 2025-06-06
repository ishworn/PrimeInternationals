@extends('admin.admin_master')
@section('admin')

<!-- Font Awesome 6 CDN -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">


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
            <a href="{{ route('customer.add') }}" class="btn btn-warning btn-rounded waves-effect waves-orange"
                style="float:right;  background-color: #FFA500; color: #555; border: 2px solid #FFA500; 
                          transition: all 0.3s ease-in-out; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);margin-right:15px;">
                <i class="fas fa-plus-circle"></i> Add Sender
            </a>
        </div>
    </div>
    <div class="container-fluid">
        <!-- Start page title -->
        <div class="row">
            <div class="col-12">
                <div class=" d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0" style="font-size: 24px; font-weight: bold;">Customers List</h4>
                </div>

                <!-- Delete form -->
                <form id="deleteForm" method="POST">
                    <a id="deleteButton"
                        class="btn btn-warning btn-rounded waves-effect waves-orange"
                        style="display:none; float:right; background-color: #B21807; color: white; 
          border: 2px solid #f44336; transition: all 0.3s ease-in-out; 
          box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); margin-right:15px; margin-bottom:10px;">
                        <i class="fas fa-trash"></i> Delete Sender
                    </a>
                </form>

            </div>
        </div>
        <!-- <div class="row">
            <div class="col-12">
                <a href="{{ route('customer.add') }}" class="btn btn-warning btn-rounded waves-effect waves-orange"
                    style="float:right;  background-color: #FFA500; color: #555; border: 2px solid #FFA500; 
                          transition: all 0.3s ease-in-out; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);margin-right:15px;margin-bottom:10px;">
                    <i class="fas fa-plus-circle"></i> Add Sender
                </a>
            </div>
        </div> -->

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
                                    <!-- <th>Dispatch To</th>
                                    <th>Dispatch Status</th> -->
                                    <th>Tracking Id</th>
                                    <th>Amount</th>

                                    <th class='payment-status'>Payment Status</th>
                                    <th style="width: 70px;">Actions</th>
                                </tr>
                            </thead>



                            <tbody>
                                @foreach($senders as $key => $sender)
                                <tr>
                                    <td><input type="checkbox" class="checkboxes"
                                            name="sender_ids[]" value="{{ $sender->id }}"></td>
                                    <td>{{ $key + 1 }}</td>
                                    <td> {{$sender->senderName}} </td>
                                    <td>{{ $sender->receiver->receiverName }}</td> <!-- Assuming receiverName field exists -->
                                    <td>{{ $sender->receiver->receiverCountry }}</td> <!-- Assuming country field exists -->
                                    <!-- <td>
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
                                    </td> -->
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
                                        <div style="display: flex; align-items: center;gap:10px;">
                                            <div class="dropdown">
                                                <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenu{{ $sender->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    ...
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenu{{ $sender->id }}">
                                                    <a href="{{ route('payments.invoice', $sender->id) }}" class="dropdown-item">
                                                        <i class="fas fa-file-invoice"></i> Billing
                                                    </a>
                                                    <a class="dropdown-item" href="{{ route('customer.edit', $sender->id) }}">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </a>
                                                    <a class="dropdown-item" href="{{ route('customer.preview', $sender->id) }}">
                                                        <i class="fas fa-eye"></i> Preview
                                                    </a>
                                                    <a class="dropdown-item" href="{{ route('customer.addweight', $sender->id) }}">
                                                        <i class="fas fa-weight"></i> Add Weight
                                                    </a>
                                                    <a class="dropdown-item" href="{{ route('customer.delete', $sender->id) }}">
                                                        <i class="fas fa-trash"></i> Delete
                                                    </a>
                                                </div>
                                            </div>
                                            <!-- <a href="{{ route('invoice.print', $sender->id) }}?autoPrint=1"  class="btn btn-info btn-sm mx-1" title="Print">
                                                <i class="fas fa-print"></i>
                                            </a> -->
                                            <iframe id="printFrame" style="display:none;"></iframe>
                                            <a href="#"
                                                onclick="printInvoice('{{ $sender->id }}'); return false;"
                                                class="btn btn-info btn-sm mx-1"
                                                title="Print">
                                                <i class="fas fa-print"></i>
                                            </a>

                                            <a href="{{ route('export.excel', $sender->id) }}"
                                                class="btn btn-dark btn-sm "
                                                title="Excel">
                                                <i class="fas fa-download"></i></a>

                                            <!-- <a href="#"
                                                class="btn btn-success btn-sm "
                                                title="Plane">
                                                <i class="fas fa-plane"></i></a>

                                                <a href="#"
                                                class="btn btn-dark btn-sm "
                                                title="Truck">
                                                <i class="fas fa-truck"></i></a>

                                                <a href="#"
                                                class="btn btn-warning btn-sm "
                                                title="Plane">
                                                <i class="fas fa-user-tie"></i></a> -->


                                            @php
                                            $dispatch = $sender->dispatch ?? null; // Fetch the dispatch related to the sender
                                            $dispatchStatus = $dispatch ? $dispatch->status : null;
                                            @endphp
                                            @if($dispatchStatus === 'dispatch')
                                            <a href="#"
                                                class="btn btn-sm d-flex align-items-center justify-content-center rounded-circle"
                                                title="Plane"
                                                style="width: 40px; height: 40px; padding: 0; background-color:#228B22 ; border: 1px solid #FFD43B;">
                                                <i class="fas fa-plane" style="color: #FFD43B;"></i>
                                            </a>

                                            @else
                                            <a href="#"
                                                class="btn btn-sm d-flex align-items-center justify-content-center rounded-circle"
                                                title="Plane"
                                                style="width: 40px; height: 40px; padding: 0; background-color: #880808; border: 1px solid #FFD43B;">
                                                <i class="fas fa-plane" style="color: #FFD43B;"></i>
                                            </a>
                                            @endif







                                        </div>
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Use this -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.1/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

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

    //delete script
    document.getElementById('deleteButton').addEventListener('click', function() {
        let checked = document.querySelectorAll('input[name="sender_ids[]"]:checked');
        if (checked.length === 0) {
            alert("Please select at least one sender to delete.");
            return;
        }

        if (confirm("Are you sure you want to delete selected sender(s)?")) {
            let ids = Array.from(checked).map(cb => cb.value);

            fetch("{{ route('customer.bulkDelete') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        sender_ids: ids
                    })
                })
                .then(res => res.json())
                .then(data => {
                    alert(data.message);
                    location.reload();
                });
        }
    });

    //Print
    // function printInvoice() {
    //     window.print();
    // }
    function printInvoice(id) {
        const iframe = document.getElementById('printFrame');
        iframe.src = `/customer/print/${id}`; // URL of your print view

        iframe.onload = function() {
            iframe.contentWindow.focus();
            iframe.contentWindow.print();
        };
    }
</script>

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