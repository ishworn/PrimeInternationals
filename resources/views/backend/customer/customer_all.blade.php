@extends('admin.admin_master')
@section('admin')

<div class="page-content">
    <div class="container-fluid">
        <!-- Start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0" style="font-size: 24px; font-weight: bold;">Customers List</h4>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 mb-3">
                <a href="{{ route('customer.add') }}" class="btn btn-warning btn-rounded waves-effect waves-orange" 
                   style="float:right; padding: 12px 20px; background-color: #FFA500; color: #ggg; border: 2px solid #FFA500; 
                          transition: all 0.3s ease-in-out; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                    <i class="fas fa-plus-circle"></i> Add Customer
                </a>
            </div>
        </div>
        <!-- End page title -->

        <div class="row">
            <div class="col-12">
                <div class="card shadow-lg">
                    <div class="card-body">
                        <h4 class="card-title mb-4" style="font-size: 22px; font-weight: bold;"></h4>

                        <table id="datatable" class="table table-bordered dt-responsive nowrap" style="width: 100%;">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th>Sl</th>
                                    <th>Sender Name</th>
                                    <th>Invoice Id</th>
                                    <th>Sender Phone</th>
                                    <th>Sender Email</th>
                                    <th>Sender Address</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($senders as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $item->senderName }}</td>
                                    <td>{{ $item->invoiceId }}</td>
                                    <td>{{ $item->senderPhone }}</td>
                                    <td>{{ $item->senderEmail }}</td>
                                    <td>{{ $item->senderAddress }}</td>
                                    <td class="d-flex justify-content-center">
                                        <a href="{{ route('customer.edit', $item->id) }}" 
                                           class="btn btn-info btn-sm mx-1" title="Edit Data">
                                           <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('customer.delete', $item->id) }}" 
                                           class="btn btn-danger btn-sm mx-1" title="Delete Data" id="delete">
                                           <i class="fas fa-trash-alt"></i>
                                        </a>
                                        <a href="{{ route('customer.preview', $item->id) }}" 
                                           class="btn btn-dark btn-sm mx-1" title="Preview">
                                           <i class="fas fa-eye"></i>
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
