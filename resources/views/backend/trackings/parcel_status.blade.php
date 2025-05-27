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
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0" style="font-size: 24px; font-weight: bold;">Trackings Status</h4>
                </div>
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
                                    <th> Sender Name </th>
                                    <th>Receiver Name</th>
                                    <th>Country</th>
                                    <th>Sender Created</th>
                                    <th>Dispatched At</th>
                                    <th>Tracking Id</th>
                                    <th>Status</th>


                                </tr>
                            </thead>



                            <tbody>
                                @foreach($sender as $key => $sender)
                                <tr>

                                    <td>{{ $key + 1 }}</td>
                                    <td> {{$sender->senderName}} </td>
                                    <td>{{ $sender->receiver->receiverName }}</td> <!-- Assuming receiverName field exists -->
                                    <td>{{ $sender->receiver->receiverCountry }}</td> <!-- Assuming country field exists -->

                                    <td> {{ $sender->created_at->format('d M, Y') }} </td> <!--sender created -->
                                    <td>
                                        {{ $sender->dispatch?->dispatched_at ? \Carbon\Carbon::parse($sender->dispatch->dispatched_at)->format('d M, Y') : 'Not dispatched' }}
                                    </td>
                                    <!--Dispatched at -->

                                    <td>{{ $sender->trackingId ?? 'N/A' }}</td>
                                    <!-- <td>{{ $sender->status }}</td>  -->
                                    <td>
                                        <form action="{{ route('senders.updateStatus', $sender->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')

                                            <div style="display: flex; align-items: center;">
                                                <div style="position: relative; width: 150px;">
                                                    <select name="status" class="form-control status-select" data-status="{{ $sender->status }}" style="padding-right: 30px;">
                                                        <option value="pending" {{ $sender->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                                        <option value="success" {{ $sender->status == 'success' ? 'selected' : '' }}>Success</option>
                                                        <option value="return" {{ $sender->status == 'return' ? 'selected' : '' }}>Return</option>
                                                    </select>


                                                    <i class="fas fa-angle-down" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); pointer-events: none;"></i>
                                                </div>

                                                <button type="submit" title="Save" class="btn btn-success btn-sm mx-1">
                                                    <i class="fas fa-save"></i>
                                                </button>
                                            </div>
                                        </form>
                                    </td>


                                </tr>
                                @endforeach
                            </tbody>

                            <!-- JavaScript -->



                            <!-- JavaScript -->



                        </table>

                    </div>
                </div>
            </div> <!-- End col -->
        </div> <!-- End row -->





    </div> <!-- container-fluid -->
</div>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<script>
    document.querySelectorAll('.status-select').forEach(select => {
        function applyColor(element, value) {
            element.classList.remove('bg-danger', 'bg-success', 'bg-secondary', 'text-white');

            if (value === 'pending') {
                element.classList.add('bg-danger', 'text-white');
            } else if (value === 'success') {
                element.classList.add('bg-success', 'text-white');
            } else if (value === 'return') {
                element.classList.add('bg-secondary', 'text-white');
            }
        }

        // On page load
        applyColor(select, select.dataset.status);

        // On change
        select.addEventListener('change', function() {
            applyColor(this, this.value);
        });
    });
</script>






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

    table th,
    table td {
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