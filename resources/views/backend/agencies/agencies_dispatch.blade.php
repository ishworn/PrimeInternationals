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
                <div class="page-title-box d-sm-flex align-items-center justify-content-between ">
                    <h4 class="mb-sm-0" style="font-size: 24px; font-weight: bold;">Dispatch List</h4>
                </div>
            </div>
        </div>
        <!-- End page title -->

        <!-- Dispatch Table -->
        <div class="row pb-20">
            <div class="col-12">
                <div class="card shadow-lg">
                    <div class="card-body">
                        <h4 class="card-title mb-4" style="font-size: 22px; font-weight: bold;"></h4>
                        <h4 class="card-title mb-4" style="font-size: 22px; font-weight: bold;"></h4>


                     
                        <table id="datatable" class="table table-bordered dt-responsive nowrap " style="width: 100%; ">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th><input type="checkbox" id="selectAll"></th> <!-- Select All Checkbox -->
                                    <th>Sl</th>
                                    <th>Invoice ID</th>
                                    <th>Sender Name</th>
                                    <th>Receiver Name</th>
                                    <th>Country</th>
                                    <th>Total Boxes</th>
                                    <th>Total Weight</th>
                
                                 
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($senders as $key => $sender)
                                <tr>
                                    <td>
                                        <input type="checkbox" class="sender-checkbox" name="sender_ids[]" value="{{ $sender->id }}">
                                    </td>
                                    <td hidden>{{ $sender->id }}</td>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $sender->invoiceId }}</td>
                                    <td>{{ $sender->senderName }}</td>
                                    <td>{{ $sender->receiver->receiverName }}</td>
                                    <td>{{ $sender->receiver->receiverCountry ?? 'N/A' }}</td>
                                    <td>{{$sender->boxes_count }}</td>
                                    <td>{{$sender->boxes_sum_box_weight }}</td>
                               

                                    <!-- Dispatch Form -->
                                   
                                </tr>
                                @endforeach

                            </tbody>

                        </table>
                        <!-- Dispatch Selected Button -->
                        <button type="button" class="btn btn-primary"  id = "dispatchSelectedBtn" >
                            Dispatch Selected
                        </button>

                        <!-- Modal -->
                        <div class="modal fade" id="dispatchModal" tabindex="-1" aria-labelledby="dispatchModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <form id="dispatchForm" action="{{ route('agencies.bulk.store') }}" method="POST">
                                    @csrf
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="dispatchModalLabel">Dispatch Items</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>

                                        <div class="modal-body">
                                           

                                            
                                            <!-- Dispatch To Dropdown -->
                                            <div class="mb-3">
                                                <label for="dispatchTo" class="form-label">Dispatch To</label>
                                                <select name="dispatch_to" id="dispatchTo" class="form-select" required>
                                                    <option value="" disabled selected>Select Dispatch To</option>
                                                    @foreach($agencies as $agency)
                                                        <option value="{{ $agency->name }}">{{ $agency->name }}</option>
                                                    @endforeach
                                                  
                                                </select> 
                                            </div>

                                            <!-- Dispatch Date -->
                                            <div class="mb-3">
                                                <label for="dispatchDate" class="form-label">Dispatch Date</label>
                                                <input type="date" name="dispatch_date" id="dispatchDate" class="form-control" required>
                                            </div>

                                            <!-- Hidden field to pass selected sender IDs -->
                                            <input type="hidden" name="selected_senders" id="selectedSenders">
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
    document.addEventListener('DOMContentLoaded', function () {
        const dispatchBtn = document.getElementById('dispatchSelectedBtn');
        const checkboxes = document.querySelectorAll('.sender-checkbox');

        dispatchBtn.addEventListener('click', function () {
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



<script>
    // Update Dispatch By
    function updateDispatchBy(element, key, senderId, ) {
        var button = document.getElementById('dispatchByDropdown' + key);
        var dispatchMethodInput = document.getElementById('dispatchBy' + senderId);
        button.textContent = element.textContent;
        dispatchMethodInput.value = element.textContent;


        if (element.textContent.trim() === 'Apex') {
            button.classList.remove('btn-secondary', 'btn-primary', 'btn-warning', 'btn-success');
            button.classList.add('btn-success'); // Green for Apex
        } else if (element.textContent.trim() === 'Dpnex') {
            button.classList.remove('btn-secondary', 'btn-primary', 'btn-success', 'btn-warning');
            button.classList.add('btn-warning'); // Yellow for Dpnex
        } else if (element.textContent.trim() === 'Pacific') {
            button.classList.remove('btn-secondary', 'btn-primary', 'btn-warning', 'btn-success');
            button.classList.add('btn-primary'); // Blue for Pacific
        } else if (element.textContent.trim() === 'Nepal Express') {
            button.classList.remove('btn-secondary', 'btn-primary', 'btn-warning', 'btn-success');
            button.classList.add('btn-info'); // Light Blue for Nepal Express
        } else if (element.textContent.trim() === 'DTDC') {
            button.classList.remove('btn-secondary', 'btn-primary', 'btn-warning', 'btn-success');
            button.classList.add('btn-danger'); // Red for DTDC
        } else if (element.textContent.trim() === 'Aramax') {
            button.classList.remove('btn-secondary', 'btn-primary', 'btn-warning', 'btn-success');
            button.classList.add('btn-dark'); // Dark for Aramax
        } else if (element.textContent.trim() === 'Nepal Post') {
            button.classList.remove('btn-secondary', 'btn-primary', 'btn-warning', 'btn-success');
            button.classList.add('btn-light'); // Light for Nepal Post
        } else if (element.textContent.trim() === 'SF International') {
            button.classList.remove('btn-secondary', 'btn-primary', 'btn-warning', 'btn-success');
            button.classList.add('btn-info'); // Light Blue for SF International
        }

    }
</script>



@endsection








