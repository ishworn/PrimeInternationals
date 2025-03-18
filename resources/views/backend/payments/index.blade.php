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
                    <h4 class="mb-sm-0" style="font-size: 24px; font-weight: bold;">Payment List</h4>
                </div>
            </div>
        </div>
        <!-- End page title -->

        <!-- Trackings Table -->
        <div class="row pb-20">
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
                                    <th style="max-width: 300px; white-space: normal;">Total Weight</th>
                                    <th>Total Box</th>
                                    <th style="width: 100px;">Amount</th>
                                    <th>Payment Method</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($senders as $key => $sender)
                                <tr>
                                    <td hidden>{{ $sender->id }}</td>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $sender->senderName }}</td>
                                    <td>{{ $sender->receiver->receiverName }}</td>
                                    <td>{{ $sender->receiver->receiverCountry }}</td>
                                    <td style="max-width: 300px; white-space: normal;">{{ $sender->shipments->actual_weight }}</td>
                                    <td>{{ $senderBoxCounts[$sender->id] ?? 'N/A'}}</td>
                                    <td>
                                        <form id="paymentForm{{ $sender->id }}" action="{{ route('payments.store') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="sender_id" value="{{ $sender->id }}">
                                            <input type="number" name="amount" class="form-control" required>
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-secondary dropdown-toggle btn-sm" type="button" id="paymentMethodDropdown{{ $key }}" data-bs-toggle="dropdown" aria-expanded="true">
                                                {{ $item->paymentMethod ?? 'Select Method' }}
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="paymentMethodDropdown{{ $key }}">
                                                <li><a class="dropdown-item" href="#" onclick="updatePaymentMethod(this, '{{ $key }}', '{{ $sender->id }}')">Bank Transfer</a></li>
                                                <li><a class="dropdown-item" href="#" onclick="updatePaymentMethod(this, '{{ $key }}', '{{ $sender->id }}')">Cash</a></li>
                                                <li><a class="dropdown-item" href="#" onclick="updatePaymentMethod(this, '{{ $key }}', '{{ $sender->id }}')">Both</a></li>
                                            </ul>
                                            <input type="hidden" name="payment_method" id="paymentMethod{{ $sender->id }}" value="">
                                        </div>
                                    </td>
                                    <td class="d-flex justify-content-center">
                                        <button type="submit" form="paymentForm{{ $sender->id }}" class="btn btn-success btn-sm mx-1" title="Save Payment">
                                            <i class="fas fa-save"></i>
                                        </button>

                                    </td>
                                    </form>
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
        /* overflow: visible; */
    }

    table th,
    table td {
        text-align: center;
        padding: 12px;
        font-size: 16px;
        /* overflow: visible; */
    }

    table tbody tr:hover {
        background-color: #f1f1f1;
        overflow: visible;
    }
    table {
        z-index: 1;
        /* overflow: visible; */
       
    }

    table th {
        background-color: #3e8e41;
        color: white;
        text-align: center;
        /* overflow: visible; */
    }

    table td {
        vertical-align: middle;
        /* overflow: visible; */
    }

    /* Ensure the dropdown is positioned absolutely */
.dropdown-menu {
  position: absolute !important;
  z-index: 1000; /* Ensure it's above other elements */
  max-height: none; /* Remove any default max height */
  /* overflow: visible; Make sure all content is visible */
}

td {
  position: relative;
   /* Make sure the dropdown is positioned relative to the <td> */
   /* overflow: visible; */
}
.page-content, .container-fluid {
    overflow: visible; /* Ensure the entire page content allows for dropdowns to be fully visible */
}

</style>

<script>
    // Update Payment Method and change button color based on selection
    function updatePaymentMethod(element, key, senderId) {
        var button = document.getElementById('paymentMethodDropdown' + key);
        var paymentMethodInput = document.getElementById('paymentMethod' + senderId);

        button.textContent = element.textContent;
        paymentMethodInput.value = element.textContent;

        if (element.textContent === 'Cash') {
            button.classList.remove('btn-secondary', 'btn-primary', 'btn-warning');
            button.classList.add('btn-success'); // Green for Cash
        } else if (element.textContent === 'Bank Transfer') {
            button.classList.remove('btn-secondary', 'btn-primary', 'btn-success');
            button.classList.add('btn-warning'); // Yellow for Bank Transfer
        } else if (element.textContent === 'Both') {
            button.classList.remove('btn-secondary', 'btn-primary', 'btn-success');
            button.classList.add('btn-primary'); // Blue for Both
        }
    }
</script>

@endsection