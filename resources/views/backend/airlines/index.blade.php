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
            <a href="#" class="btn btn-warning btn-rounded waves-effect waves-orange" data-bs-toggle="modal" data-bs-target="#addairlines"
                style="float:right;  background-color: #FFA500; color: #555; border: 2px solid #FFA500; 
                          transition: all 0.3s ease-in-out; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);margin-right:15px;">
                <i class="fas fa-plus-circle"></i> Add Airlines
            </a>
        </div>
    </div>
    <div class="modal fade" id="addairlines" tabindex="-1" aria-labelledby="addairlinesModelLable" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addairlinesModelLable">Add New Airlines</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addUserForm" action="{{route('airlines.create')}}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>


                        <button type="submit" class="btn btn-primary">Save Airlines</button>
                    </form>
                </div>
            </div>
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