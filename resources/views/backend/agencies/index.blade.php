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
                <a href="{{ route('customer.add') }}" class="btn btn-warning btn-rounded waves-effect waves-orange"
                    style="float:right;  background-color: #FFA500; color: #555; border: 2px solid #FFA500; 
                          transition: all 0.3s ease-in-out; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);margin-right:15px;">
                    <i class="fas fa-plus-circle"></i> Add Agencies
                </a>
            </div>
        </div>
    <div class="container-fluid">
        <!-- Start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0" style="font-size: 24px; font-weight: bold;">Agencies List</h4>
                </div>
            </div>
        </div>
        
        <!-- End page title -->


    </div> <!-- container-fluid -->
</div>






@endsection