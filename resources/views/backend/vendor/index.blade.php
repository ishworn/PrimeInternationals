@extends('admin.admin_master')
@section('admin')

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<div class="row">
        <div class="col-12 mb-3" style="margin-top: 6rem;">
            <a href="javascript:history.back()" class="btn btn-warning btn-rounded no-print"
                style="font-size: 15px; display: inline-flex; align-items: center; text-decoration: none; 
          background-color: #FFD700; color: black; padding: 10px 10px; border-radius: 5px; 
          margin-bottom: 15px; margin-top: 5px; margin-left: 20px;">
                <i class="fas fa-arrow-left" style="margin-right: 5px;"></i> Back
            </a>
</div>

<div class="container-fluid" >
    <h4 class="mb-4 font-weight-bold">Vendors Details</h4>

    @foreach($vendors as $vendor)
    <div class="card mb-4 shadow">
        <div class="card-header bg-info text-white">
            <strong>{{ $vendor->name }}</strong> (Vendor)
        </div>
        <div class="card-body">
            @if($vendor->senders->isEmpty())
            <p>No senders added by this vendor.</p>
            @else
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-dark bg-primary text-white">
                        <tr>
                            <th>#</th>
                            <th>Sender Name</th>
                            <th>Receiver Name</th>
                            <th>Country</th>
                            <th>Tracking ID</th>
                            <th>Amount</th>

                            <th class='payment-status'>Payment Status</th>
                            <th style="width: 70px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($vendor->senders as $key => $sender)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $sender->senderName }}</td>
                            <td>{{ $sender->receiver->receiverName ?? 'N/A' }}</td>
                            <td>{{ $sender->receiver->receiverCountry ?? 'N/A' }}</td>
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
                </table>
            </div>
            @endif
        </div>
    </div>
    @endforeach
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


@endsection