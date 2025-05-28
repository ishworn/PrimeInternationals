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
                                    <th>Sl</th>
                                    <th>Sender Name</th>
                                    <th>Receiver Name</th>
                                    <th>Country</th>
                                    <th>Postal Code</th>
                                    <th>Dispatch To</th>      
                                    <th>Dispatch Date</th>
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
                                    <td>{{ $sender->receiver->receiverCountry ?? 'N/A' }}</td>
                                    <td>{{ $sender->receiver->receiverPostalCode ?? 'N/A' }}</td>

                                    <!-- Dispatch Form -->
                                    <form id="dispatchForm{{ $sender->id }}" action="{{ route('dispatch.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="sender_id" value="{{ $sender->id }}">

                                        <!-- Dispatch By Dropdown -->
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-secondary dropdown-toggle btn-sm" type="button" id="dispatchByDropdown{{ $key }}" data-bs-toggle="dropdown" aria-expanded="true">
                                                    {{ $dispatch->dispatch_by ?? 'Select Dispatch By' }}
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="dispatchByDropdown{{ $key }}">
                                                    <li><a class="dropdown-item" href="#" onclick="updateDispatchBy(this, '{{ $key }}', '{{ $sender->id }}',)">Apex</a></li>
                                                    <li><a class="dropdown-item" href="#" onclick="updateDispatchBy(this, '{{ $key }}', '{{ $sender->id }}', )">Dpnex</a></li>
                                                    <li><a class="dropdown-item" href="#" onclick="updateDispatchBy(this, '{{ $key }}', '{{ $sender->id }}', )">Pacific</a></li>
                                                    <li><a class="dropdown-item" href="#" onclick="updateDispatchBy(this, '{{ $key }}', '{{ $sender->id }}', )">Nepal Express</a></li>
                                                    <li><a class="dropdown-item" href="#" onclick="updateDispatchBy(this, '{{ $key }}', '{{ $sender->id }}', )">DTDC</a></li>
                                                    <li><a class="dropdown-item" href="#" onclick="updateDispatchBy(this, '{{ $key }}', '{{ $sender->id }}', )">Aramax</a></li>
                                                    <li><a class="dropdown-item" href="#" onclick="updateDispatchBy(this, '{{ $key }}', '{{ $sender->id }}', )">Nepal Post</a></li>
                                                    <li><a class="dropdown-item" href="#" onclick="updateDispatchBy(this, '{{ $key }}', '{{ $sender->id }}', )">SF International</a></li>
                                                </ul>
                                                <input type="hidden" name="dispatch_by" id="dispatchBy{{ $sender->id }}" value="">
                                            </div>
                                        </td>

                                      
                                   

                                        <!-- Dispatch Date -->
                                        <td>
                                            <input type="datetime-local" name="dispatched_at" class="form-control">
                                        </td>

                                        <!-- Submit Button -->
                                        <td class="d-flex justify-content-center">
                                            <button type="submit" form="dispatchForm{{ $sender->id }}" class="btn btn-success btn-sm mx-1" title="Save Dispatch">
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
    }
    table{
        margin-top:20px;
    }
    /* Ensure dropdown opens downwards */
.dropdown-menu {
    position: absolute !important;

    z-index: 3000; /* Ensure it's above other elements */
 
    /* Adds a small gap below the button */
    max-height: none; /* Remove any default max height */
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

    .page-content, .container-fluid {
        overflow: visible;
    }
</style>

<script>
    // Update Dispatch By
    function updateDispatchBy(element, key, senderId, ) {
       var button =  document.getElementById('dispatchByDropdown' + key);
       var dispatchMethodInput =  document.getElementById('dispatchBy' + senderId);
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
