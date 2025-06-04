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
        <div class="container mt-4">
            <h2 class="mb-4">Shipment Details - ID: {{ $shipment->id }}</h2>

            <!-- Shipment Info -->
            <div class="card mb-4">
                <div class="card-header">Shipment Info</div>
                <div class="card-body">
                    <p><strong>Shipment Number:</strong> {{ $shipment->shipment_number }}</p>
                    <p><strong>Sender IDs:</strong> {{ implode(', ', $shipment->sender_id) }}</p>
                    <p><strong>Total Weight:</strong> {{ $totalWeight }} kg</p>
                    <p><strong>Total Boxes:</strong> {{ $totalBoxes }}</p>
                    
              
                    <p><strong>Created At:</strong> {{ $shipment->created_at }}</p>
                 
                    <!-- Add other shipment fields as needed -->
                </div>
            </div>

            <!-- Senders Info -->
            @foreach($senders as $sender)
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-primary text-white">
                    Sender Group #{{ $loop->iteration }}
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Sender Column -->
                        <div class="col-md-4 border-end">
                            <h5>Sender</h5>
                             <p><strong>InvoiceId:</strong> {{ $sender->invoiceId }}</p>
                            <p><strong>Name:</strong> {{ $sender->senderName }}</p>
                            <p><strong>Phone:</strong> {{ $sender->senderPhone }}</p>
                            <p><strong>Email:</strong> {{ $sender->senderEmail }}</p>
                            <p><strong>Address:</strong> {{ $sender->senderAddress }}</p>
                           
                        </div>

                        <!-- Receiver Column -->
                        <div class="col-md-4 border-end">
                            <h5>Receiver</h5>
                            @if($sender->receiver)
                            <p><strong>Name:</strong> {{ $sender->receiver->receiverName }}</p>
                            <p><strong>Phone:</strong> {{ $sender->receiver->receiverPhone }}</p>
                            <p><strong>Email:</strong> {{ $sender->receiver->receiverEmail }}</p>
                            <p><strong>Address:</strong> {{ $sender->receiver->receiverAddress }}</p>
                            @else
                            <p class="text-muted">No receiver found</p>
                            @endif
                        </div>

                        <!-- Boxes Column -->
                        <div class="col-md-4">
                            <h5>Boxes</h5>
                            @if($sender->boxes && $sender->boxes->count())
                            <ul class="list-group">
                                @foreach($sender->boxes as $box)
                                <li class="list-group-item">
                                    <strong>Box #{{ $loop->iteration }}</strong><br>
                                   
                                    Weight: {{ $box->box_weight }} kg<br>
                                   
                                </li>
                                @endforeach
                            </ul>
                            @else
                            <p class="text-muted">No boxes found</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach


        </div>

    </div>
</div>


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



@endsection