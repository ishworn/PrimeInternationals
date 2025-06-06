@extends('admin.admin_master')
@section('admin')

<div class="page-content">
    @if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    
    <div class="container-fluid">
        <!-- Start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Customers List</h4>
                    <div class="page-title-right">
                        <a href="javascript:history.back()" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i> Back
                        </a>
                        <a href="{{ route('customer.add') }}" class="btn btn-warning ms-2 btn-sm">
                            <i class="fas fa-plus-circle me-1"></i> Add Sender
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- End page title -->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="datatable" class="table table-hover table-bordered dt-responsive nowrap w-100">
                                <thead class="table-light">
                                    <tr>
                                        <th width="5%">Sl</th>
                                        <th>Sender Name</th>
                                        <th>Receiver Name</th>
                                        <th>Country</th>
                                        <th>Dispatch To</th>
                                        <th>Status</th>
                                        <th>Tracking ID</th>
                                        <th>Amount</th>
                                        <th>Payment</th>
                                        <th width="12%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($senders as $key => $sender)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $sender->senderName }}</td>
                                        <td>{{ $sender->receiver->receiverName ?? 'N/A' }}</td>
                                        <td>{{ $sender->receiver->receiverCountry ?? 'N/A' }}</td>
                                        <td>
                                            @php
                                            $dispatch = $sender->dispatch ?? null;
                                            $dispatchBy = $dispatch ? $dispatch->dispatch_by : null;
                                            @endphp

                                            @if(!$dispatchBy)
                                            <span class="badge bg-secondary">N/A</span>
                                            @elseif($dispatchBy === 'Apex')
                                            <span class="badge bg-success">Apex</span>
                                            @elseif($dispatchBy === 'Dpnex')
                                            <span class="badge bg-warning text-dark">Dpnex</span>
                                            @elseif($dispatchBy === 'Pacific')
                                            <span class="badge bg-primary">Pacific</span>
                                            @elseif($dispatchBy === 'Nepal Express')
                                            <span class="badge bg-info text-dark">Nepal Express</span>
                                            @elseif($dispatchBy === 'DTDC')
                                            <span class="badge bg-danger">DTDC</span>
                                            @elseif($dispatchBy === 'Aramax')
                                            <span class="badge bg-dark">Aramax</span>
                                            @elseif($dispatchBy === 'Nepal Post')
                                            <span class="badge bg-light text-dark">Nepal Post</span>
                                            @elseif($dispatchBy === 'SF International')
                                            <span class="badge bg-info">SF International</span>
                                            @else
                                            <span class="badge bg-warning text-dark">Unknown</span>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                            $dispatch = $sender->dispatch ?? null;
                                            $dispatchStatus = $dispatch ? $dispatch->status : null;
                                            @endphp

                                            @if(!$dispatchStatus)
                                            <span class="badge bg-danger">Pending</span>
                                            @elseif($dispatchStatus === 'pending')
                                            <span class="badge bg-danger">Pending</span>
                                            @elseif($dispatchStatus === 'dispatch')
                                            <span class="badge bg-success">Dispatched</span>
                                            @else
                                            <span class="badge bg-warning text-dark">Unknown</span>
                                            @endif
                                        </td>
                                        <td>{{ $sender->trackingId ?? 'N/A' }}</td>
                                        <td>{{ $sender->payments->total_paid ?? 'N/A' }}</td>
                                        <td>
                                            @php
                                            $paymentStatus = optional($sender->payments)->status ?? 'pending';
                                            @endphp

                                            @if($paymentStatus === 'pending')
                                            <span class="badge bg-danger">Pending</span>
                                            @elseif($paymentStatus === 'partial')
                                            <span class="badge bg-warning text-dark">Partial</span>
                                            @elseif($paymentStatus === 'completed')
                                            <span class="badge bg-success">Completed</span>
                                            @else
                                            <span class="badge bg-secondary">Unknown</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('customer.edit', $sender->id) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="{{ route('customer.preview', $sender->id) }}" class="btn btn-sm btn-outline-info" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('customer.addweight', $sender->id) }}" class="btn btn-sm btn-outline-warning" title="Add Weight">
                                                    <i class="fas fa-weight"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        border: none;
    }
    
    .card-body {
        padding: 1.5rem;
    }
    
    .table thead th {
        border-bottom-width: 1px;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
        background-color: #f8f9fa;
    }
    
    .table td, .table th {
        vertical-align: middle;
        padding: 0.75rem;
    }
    
    .badge {
        font-weight: 500;
        padding: 0.35em 0.5em;
        font-size: 0.75em;
        letter-spacing: 0.5px;
    }
    
    .btn-group .btn {
        padding: 0.25rem 0.5rem;
        border-radius: 0.25rem;
    }
    
    .btn-outline-primary {
        color: #3b7ddd;
        border-color: #3b7ddd;
    }
    
    .btn-outline-primary:hover {
        background-color: #3b7ddd;
        color: white;
    }
    
    .btn-outline-info {
        color: #17a2b8;
        border-color: #17a2b8;
    }
    
    .btn-outline-info:hover {
        background-color: #17a2b8;
        color: white;
    }
    
    .btn-outline-warning {
        color: #ffc107;
        border-color: #ffc107;
    }
    
    .btn-outline-warning:hover {
        background-color: #ffc107;
        color: #212529;
    }
    
    .page-title-box {
        padding-bottom: 1.5rem;
    }
    
    @media (max-width: 768px) {
        .table-responsive {
            border: 0;
        }
        
        .table thead {
            display: none;
        }
        
        .table tr {
            display: block;
            margin-bottom: 1rem;
            border: 1px solid #dee2e6;
            border-radius: 0.25rem;
        }
        
        .table td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem;
            border-bottom: 1px solid #dee2e6;
        }
        
        .table td:before {
            content: attr(data-label);
            font-weight: 600;
            margin-right: 1rem;
            text-transform: uppercase;
            font-size: 0.75rem;
            color: #6c757d;
        }
        
        .table td:last-child {
            border-bottom: 0;
        }
        
        .btn-group {
            width: 100%;
            justify-content: flex-end;
        }
    }
</style>

<script>
    // Make table responsive by adding data-labels on mobile
    document.addEventListener('DOMContentLoaded', function() {
        const headers = document.querySelectorAll('thead th');
        const rows = document.querySelectorAll('tbody tr');
        
        if (window.innerWidth < 768) {
            rows.forEach(row => {
                const cells = row.querySelectorAll('td');
                cells.forEach((cell, index) => {
                    if (index < headers.length) {
                        cell.setAttribute('data-label', headers[index].textContent.trim());
                    }
                });
            });
        }
        
        window.addEventListener('resize', function() {
            if (window.innerWidth < 768) {
                rows.forEach(row => {
                    const cells = row.querySelectorAll('td');
                    cells.forEach((cell, index) => {
                        if (index < headers.length) {
                            cell.setAttribute('data-label', headers[index].textContent.trim());
                        }
                    });
                });
            } else {
                rows.forEach(row => {
                    const cells = row.querySelectorAll('td');
                    cells.forEach(cell => {
                        cell.removeAttribute('data-label');
                    });
                });
            }
        });
    });
</script>

@endsection