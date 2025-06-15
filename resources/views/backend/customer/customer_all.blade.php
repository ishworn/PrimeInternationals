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
                <div class="d-flex justify-content-between align-items-center mb-3">
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

        <div class="row">
            <div class="col-12">
                <div class="card shadow-lg">
                    <div class="card-body">
                        <h4 class="card-title m-0 " style="font-size: 22px; font-weight: bold;"></h4>
                        <!-- Vendor Filter Dropdown  -->
                        <div class="mb-2"  style="text-align: right;">
                            <div class="dropdown d-inline-block  ">
                                <button class="btn  dropdown-toggle" type="button" id="vendorDropdown"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="background-color:#088F8F; color:white">
                                    @if(request('vendor_id'))
                                    {{ \App\Models\User::find(request('vendor_id'))->name ?? 'All Vendors' }}
                                    @else
                                    All Vendors
                                    @endif
                                </button>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="vendorDropdown">
                                    <a class="dropdown-item" href="{{ route('customer.all') }}">All Senders</a>
                                    @foreach($vendors as $vendor)
                                    <a class="dropdown-item" href="{{ route('customer.all', ['vendor_id' => $vendor->id]) }}">
                                        {{ $vendor->name }}
                                    </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <table id="datatable" class="table table-bordered dt-responsive nowrap" style="width: 100%;">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th><input type="checkbox" id="selectAllCheckbox"></th>
                                    <th>Sl</th>
                                    <th> Sender Name </th>
                                    <th>Receiver Name</th>
                                    <th>Country</th>
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
                                    <td>{{ $sender->receiver->receiverName }}</td>
                                    <td>{{ $sender->receiver->receiverCountry }}</td>
                                    <td>{{ $sender->trackingId ?? 'N/A' }}</td>
                                    <td>{{ $sender->payments->total_paid ?? 'N/A' }}</td>

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

                                            @php
                                            $dispatch = $sender->dispatch ?? null;
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
                        </table>
                    </div>
                </div>
            </div> <!-- End col -->
        </div> <!-- End row -->
    </div> <!-- container-fluid -->
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.1/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

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

    $(document).on('change', '.checkboxes', function() {
        updateDeleteButtonVisibility();
    });

    $('#selectAllCheckbox').change(function() {
        $('.checkboxes').prop('checked', $(this).prop('checked'));
        updateDeleteButtonVisibility();
    });

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

    function printInvoice(id) {
        const iframe = document.getElementById('printFrame');
        iframe.src = `/customer/print/${id}`;

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

    .payment-status {
        width: 100px;
    }

    /* Style for vendor dropdown */
    #vendorDropdown {
        background-color: #3e8e41;
        border-color: #3e8e41;
        margin-right: 15px;
    }

    #vendorDropdown:hover {
        background-color: #367c39;
        border-color: #367c39;
    }

    /* Ensure dropdown menu aligns to the right */
    .dropdown-menu-right {
        right: 0;
        left: auto;
    }

    /* Flex container for header */
    .d-flex.justify-content-between {
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
    }

    .ml-auto {
        margin-left: auto;
    }
</style>

@endsection