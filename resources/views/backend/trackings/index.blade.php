@extends('admin.admin_master')

@section('admin')

<div class="page-content">
    <div class="container-fluid">
        <!-- Start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0" style="font-size: 24px; font-weight: bold;">Trackings List</h4>
                </div>
            </div>
        </div>
        <!-- End page title -->

        <div class="row">
            <div class="col-12 mb-3">
                <a href="{{ route('trackings.create') }}" class="btn btn-warning btn-rounded waves-effect waves-orange" 
                   style="float:right; padding: 12px 20px; background-color: #FFA500; color: #fff; border: 2px solid #FFA500; 
                          transition: all 0.3s ease-in-out; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                    <i class="fas fa-plus-circle"></i> Add Tracking
                </a>
            </div>
        </div>

        <!-- Trackings Table -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-lg">
                    <div class="card-body">
                        <h4 class="card-title mb-4" style="font-size: 22px; font-weight: bold;"></h4>

                        <table id="datatable" class="table table-bordered dt-responsive nowrap" style="width: 100%;">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th>Sl</th>
                                    <th>Tracking Number</th>
                                    <th>Receiver Name</th>
                                    <th>Location</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($trackings as $key => $tracking)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $tracking->tracking_number }}</td>
                                    <td>{{ $tracking->receiver_name }}</td>
                                    <td>{{ $tracking->location }}</td>
                                    <td class="d-flex justify-content-center">
                                        <a href="{{ route('trackings.edit', $tracking->id) }}" 
                                           class="btn btn-info btn-sm mx-1" title="Edit Data">
                                           <i class="fas fa-edit"></i>
                                        </a>

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
        overflow: hidden;
    }

    table th, table td {
        text-align: center;
        padding: 12px;
        font-size: 16px;
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
</style>

@endsection



