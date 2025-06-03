@extends('admin.admin_master')

@section('admin')

<div class="page-content">
    @if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif
    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif
    <a href="javascript:history.back()" class="btn btn-warning btn-rounded no-print"
        style="font-size: 15px; display: inline-flex; align-items: center; text-decoration: none; 
          background-color: #FFD700; color: black; padding: 10px 10px; border-radius: 5px; 
          margin-bottom: 15px; margin-top: 5px; margin-left: 20px;">
        <i class="fas fa-arrow-left" style="margin-right: 5px;"></i> Back
    </a>
    <div class="container-fluid">

        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0" style="font-size: 24px; font-weight: bold;">Agency Payment Details</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card shadow-lg">
                    <div class="card-body">

                        <table id="datatable" class="table table-striped table-bordered dt-responsive nowrap" style="width: 100%;">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th>Sl</th>
                                    <th>Receiver Name</th>
                                    <th>Country</th>
                                    <th>Dispatch To</th>
                                    <th>Total Weight</th>
                                    <th>Receive Amount</th>

                                    <th style="width: 130px;">Pay Amount</th>
                                    <th style="width: 130px;">Pay Method</th>


                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($senders as $key => $sender)
                                <tr>
                                    <td>{{ $key + 1 }}</td>

                                    <td>{{ $sender->receiver->receiverName }}</td>
                                    <td>{{ $sender->receiver->receiverCountry ?? 'N/A'  }}</td>
                                    <td>{{ $sender->dispatch->dispatch_by }}</td>
                                    <td>{{$sender->boxes_sum_box_weight }} Kg</td>
                                    <td>{{ $sender-> payments->total_paid }}</td>

                                    <td>

                                        <form action="{{ route('agencies.debits') }}" method="POST" id="payForm{{ $sender->id }}">
                                            @csrf

                                            <input type="hidden" name="sender_id" value="{{ $sender->id }}">
                                            <input type="number" name="debits" class="form-control">
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-secondary dropdown-toggle btn-sm" type="button"
                                                id="paymentMethodDropdown{{ $key }}" data-bs-toggle="dropdown"
                                                aria-expanded="false">
                                                Select Method
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="paymentMethodDropdown{{ $key }}">
                                                <li><a class="dropdown-item" href="#" onclick="updatePaymentMethod(this, '{{ $key }}', '{{ $sender->id }}')">Bank Transfer</a></li>
                                                <li><a class="dropdown-item" href="#" onclick="updatePaymentMethod(this, '{{ $key }}', '{{ $sender->id }}')">Cash</a></li>
                                                <li><a class="dropdown-item" href="#" onclick="updatePaymentMethod(this, '{{ $key }}', '{{ $sender->id }}')">Both</a></li>
                                            </ul>
                                            <input type="hidden" name="paymethod_debits" id="paymentMethod{{ $sender->id }}" value="">
                                        </div>
                                    </td>




                                    <td>
                                        <button type="submit" form="payForm{{ $sender->id }}"
                                            class="btn btn-success btn-sm mx-1" title="Save Payment">
                                            <i class="fas fa-save"></i>
                                        </button>
                                    </td>
                                    </form>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>


                </div>
            </div>
        </div> <!-- End col -->
    </div>
</div>
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

    .bg-light {
        background-color: #f8f9fa;
    }
</style>
<script>
    function updatePaymentMethod(element, key, senderId) {
        const button = document.getElementById(`paymentMethodDropdown${key}`);
        const paymentMethodInput = document.getElementById(`paymentMethod${senderId}`);
        const cashField = document.getElementById(`cashField${senderId}`);
        const bankField = document.getElementById(`bankField${senderId}`);


        const method = element.textContent;
        button.textContent = method;
        paymentMethodInput.value = method.toLowerCase().replace(' ', '_');

        cashField.style.display = 'none';
        bankField.style.display = 'none';
        cashInput.required = false;
        bankInput.required = false;

        cashInput.value = '';
        bankInput.value = '';

        if (method === 'Cash') {
            button.classList.remove('btn-secondary', 'btn-primary', 'btn-warning');
            button.classList.add('btn-success');
            cashField.style.display = 'block';
            cashInput.readOnly = !!cashInput.value;
        } else if (method === 'Bank Transfer') {
            button.classList.remove('btn-secondary', 'btn-primary', 'btn-success');
            button.classList.add('btn-warning');
            bankField.style.display = 'block';
            bankInput.readOnly = !!bankInput.value;
        } else if (method === 'Both') {
            button.classList.remove('btn-secondary', 'btn-success', 'btn-warning');
            button.classList.add('btn-primary');
            cashField.style.display = 'block';
            bankField.style.display = 'block';
            cashInput.readOnly = !!cashInput.value;
            bankInput.readOnly = !!bankInput.value;
        }
    }
</script>

@endsection