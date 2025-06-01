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
    
</div>
<div class="container-fluid">
    <!-- Start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0" style="font-size: 24px; font-weight: bold;">Airlines List</h4>
            </div>
        </div>
    </div>

    <table id="datatable" class="table table-bordered table-striped " style="width: 100%; margin-top: 50px;">
        <thead class="thead-dark">
            <tr>
                <th>Airlines Name</th>

                <th>Total Weight </th>
                <th style="width: 130px;">Action</th>


            </tr>
        </thead>
        

    </table>





    <!-- End page title -->


</div> <!-- container-fluid -->
</div>








@endsection