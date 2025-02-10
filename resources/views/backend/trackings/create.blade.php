@extends('admin.admin_master')

@section('admin')
<div class="page-content">
<a href="javascript:history.back()" class="btn btn-light btn-rounded" style="font-size: 20px; display: inline-flex; align-items: center; text-decoration: none;">
    <i class="fas fa-arrow-left" style="margin-right: 8px;"></i> Back
</a>
    <div class="container-fluid">
        <!-- Start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0" style="font-size: 24px; font-weight: bold;">Create New Tracking</h4>
                </div>
            </div>
        </div>
        <!-- End page title -->

        <!-- Start form section -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-lg">
                    <div class="card-body">
                        <h4 class="card-title mb-4" style="font-size: 22px; font-weight: bold;">Add a New Tracking</h4>
                        
                        <form action="{{ route('trackings.store') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="tracking_number" style="font-size: 16px;">Tracking Number:</label>
                                <input type="text" name="tracking_number" class="form-control" required placeholder="Enter Tracking Number" style="font-size: 16px; padding: 10px;">
                            </div>

                            <div class="form-group">
                                <label for="receiver_name" style="font-size: 16px;">Receiver Name:</label>
                                <input type="text" name="receiver_name" class="form-control" required placeholder="Enter Receiver Name" style="font-size: 16px; padding: 10px;">
                            </div>

                            <div class="form-group">
                                <label for="location" style="font-size: 16px;">Location:</label>
                                <input type="text" name="location" class="form-control" required placeholder="Enter Location" style="font-size: 16px; padding: 10px;">
                            </div>

                            <button type="submit" class="btn btn-warning btn-rounded waves-effect waves-orange" 
                                    style="background-color: #FFA500; color: #fff; border: 2px solid #FFA500; 
                                    transition: all 0.3s ease-in-out; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); padding: 12px 20px;">
                                <i class="fas-circle"></i> submit
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- End form section -->

    </div>
</div>

<!-- Add custom styles for the form -->
<style>
    .card {
        border-radius: 8px;
        overflow: hidden;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .btn {
        transition: all 0.3s ease;
    }

    .btn:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
    }

    .form-control {
        border-radius: 8px;
        padding: 10px;
        font-size: 16px;
        width: 100%;
        border: 1px solid #ddd;
        transition: border-color 0.3s ease;
    }

    .form-control:focus {
        border-color: #FFA500;
    }

    label {
        font-size: 16px;
        font-weight: bold;
    }

    .card-body {
        background-color: #fff;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }
</style>

@endsection
