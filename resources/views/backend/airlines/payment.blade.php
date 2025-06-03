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
                <div class="page-title-box d-sm-flex align-items-center justify-content-between ">
                    <h4 class="mb-sm-0" style="font-size: 24px; font-weight: bold;">Airline Payment List</h4>
                </div>
            </div>
        </div>
        <!-- End page title -->

        <!-- Dispatch Table -->
        <div class="row pb-20">
            <div class="col-12">
                <div class="card shadow-lg">
                    <div class="card-body">

                        <table id="datatable" class="table table-bordered dt-responsive nowrap " style="width: 100%; ">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th><input type="checkbox" id="selectAll"></th> <!-- Select All Checkbox -->
                                     <th>Sl</th>
                                    <th>Shipment ID</th>
                                    <th>Sender Id</th>
                                    <th>Total Boxes</th>
                                    <th>Total Weight</th>


                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($shipments as $key => $item)
                                <tr>
                                    <td>
                                        <input type="checkbox" class="sender-checkbox" name="sender_ids[]" value="{{ $item->id }}">
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $item->shipment_number }}</td>


                                    <td>{{ implode(', ', $item->sender_id) }}</td>
                                    <td>{{ $item->total_boxes }}</td>
                                    @endforeach
                                  
                            </tbody>
                        </table>
                        <!-- Dispatch Selected Button -->
                        <button type="button" class="btn btn-primary" id="paymentSelectedBtn" data-bs-toggle="modal" data-bs-target="#paymentModal">
                            Payment Selected
                        </button>

                        <!-- Modal -->
                        <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="dispatchModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <form id="dispatchForm" action="{{ route('airline.payment.store') }}" method="POST">
                                    @csrf
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="dispatchModalLabel">Dispatch Items</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>

                                        <div class="modal-body">
                                          
                                            <div class="mb-3">
                                                <label for="dispatchDate" class="form-label">Flight Charge </label>
                                                <input type="decimal" name="flight_charge" id="flightcharge" class="form-control" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="dispatchDate" class="form-label">CustomClerence </label>
                                                <input type="decimal" name="custom_clearance" id="customclearance" class="form-control" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="dispatchDate" class="form-label">Agencies Charge </label>
                                                <input type="decimal " name="agencies_charge" id="agenciesCharge" class="form-control" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="dispatchDate" class="form-label">Date </label>
                                                <input type="date" name="payment_date" id="paymentDate" class="form-control" required>
                                            </div>

                                            <!-- Hidden field to pass selected sender IDs -->
                                            <input type="hidden" name="selected_shipment" id="selectedSenders">
                                        </div>

                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-success">Submit</button>
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>


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
    }

    table {
        margin-top: 20px;
    }

    /* Ensure dropdown opens downwards */
    .dropdown-menu {
        position: absolute !important;

        z-index: 3000;
        /* Ensure it's above other elements */

        /* Adds a small gap below the button */
        max-height: none;
        /* Remove any default max height */
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

    table {
        z-index: 1;
    }

    table th {
        background-color: #3e8e41;
        color: white;
        text-align: center;
    }

    table td {
        vertical-align: middle;
    }



    td {
        position: relative;
    }

    .page-content,
    .container-fluid {
        overflow: visible;
    }
</style>

<script src="https://cdn.tailwindcss.com"></script>

<script>
    // Select all checkboxes
    document.getElementById('selectAll').addEventListener('change', function() {
        const isChecked = this.checked;
        document.querySelectorAll('.sender-checkbox').forEach(checkbox => {
            checkbox.checked = isChecked;
        });
    });

    // Handle agency selection
    function selectAgency(agency) {
        document.getElementById('bulkDispatchBy').value = agency;
        document.getElementById('bulkDispatchDropdown').innerText = agency;
    }
    document.querySelector('#dispatchForm').addEventListener('submit', function(e) {
        const selectedCheckboxes = document.querySelectorAll('.sender-checkbox:checked');
        const selectedValues = Array.from(selectedCheckboxes).map(cb => cb.value);
        document.getElementById('selectedSenders').value = selectedValues.join(',');
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dispatchBtn = document.getElementById('dispatchSelectedBtn');
        const checkboxes = document.querySelectorAll('.sender-checkbox');

        dispatchBtn.addEventListener('click', function() {
            const isAnyChecked = Array.from(checkboxes).some(cb => cb.checked);

            if (!isAnyChecked) {
                alert('Please select at least one sender before dispatching.');
            } else {
                // Manually show modal using Bootstrap's JS API
                const dispatchModal = new bootstrap.Modal(document.getElementById('dispatchModal'));
                dispatchModal.show();
            }
        });
    });
</script>






@endsection