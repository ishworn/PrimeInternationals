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
                    <h4 class="mb-sm-0" style="font-size: 24px; font-weight: bold;">Shipment List</h4>
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
                            <thead>
                                <tr>    
                                    <th>Sl</th>
                                    <th>Shipment ID</th>
                                    <th>Sender Id</th>
                                    
                                    <th>Actions</th>
                                </tr>
                            </thead>
                        
                            
                           

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



@endsection
