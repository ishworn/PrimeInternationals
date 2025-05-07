@extends('admin.admin_master')
@section('admin')

<div class="page-content">
<a href="javascript:history.back()" class="btn btn-warning btn-rounded no-print" 
style="font-size: 15px; display: inline-flex; align-items: center; text-decoration: none; 
          background-color: #FFD700; color: black; padding: 10px 10px; border-radius: 5px; 
          margin-bottom: 15px; margin-top: 10px; margin-left: 20px;">
    <i class="fas fa-arrow-left" style="margin-right: 5px;"></i> Back
</a>
    <div class="container-fluid">
        <!-- Start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0" style="font-size: 24px; font-weight: bold;">Add Weight</h4>
                </div>
            </div>
        </div>

        <div class="container mx-auto p-6">

    

    <!-- Display sender name -->
    <p class="mb-4"><strong class="font-semibold">Sender Name:</strong> {{ $sender->senderName }}</p>

    <!-- Table for boxes and weight input -->
    <form method="POST" action="{{ route('customer.updateweight', $sender->id) }}"  id = "myForm" enctype="multipart/form-data" class="space-y-4">
        @csrf
        <input type="hidden" name="id" value="{{ $sender->id }}">

        <table class="min-w-full table-auto border-collapse">
            <thead>
                <tr>
                    <th class="px-4 py-2 text-left bg-gray-100">Box Number</th>
                    <th class="px-4 py-2 text-left bg-gray-100">Box Weight</th>
                    <th class="px-4 py-2 text-left bg-gray-100">Box Dimensions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($sender->boxes as $box)
                    <tr>
                        <td class="px-4 py-2 border-b">{{ $box->box_number }}</td>
                        <td class="px-4 py-2 border-b">
                            <input type="number" name="boxes[{{ $box->id }}][weight]" step="0.01" value="{{ $box->box_weight }}" class="form-input px-3 py-2 border border-gray-300 rounded-md" placeholder="Enter weight" required>
                        </td>
                        <td class="px-4 py-2 border-b">
                            <input type="string" name="boxes[{{ $box->id }}][dimension]" value="{{ $box->box_dimension }}"  class="form-input px-3 py-2 border border-gray-300 rounded-md" placeholder="Enter dimensions" >
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="flex justify-end">
            <button type="submit" class="  px-6 py-2 rounded-md hover:bg-blue-600 transition">Save Weights</button>
        </div>
    </form>

</div>


    
        <!-- End page title -->

     

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












