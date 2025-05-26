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
                    <h4 class="mb-sm-0" style="font-size: 24px; font-weight: bold;">Payment List</h4>
                </div>
            </div>
        </div>

        <div class="row pb-20">
            <div class="col-12">
                <div class="card shadow-lg">
                    <div class="card-body">
                        <table id="datatable" class="table table-bordered dt-responsive nowrap" style="width: 100%;">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th>Sl</th>
                                    <th>Sender Name</th>
                                    <th>Receiver Name</th>
                                    <th>Country</th>
                                    <th>Total Weight</th>
                                    <th style="width: 130px;">Bill Amount</th>
                                    <th style="width: 150px;">Payment Method</th>
                                    <th style="width: 200px;">Amount</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($senders as $key => $sender)
                                <tr>
                                    <input type="hidden" hidden name="sender_id" value="{{ $sender->id }}">
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $sender->senderName }}</td>
                                    <td>{{ $sender->receiver->receiverName }}</td>
                                    <td>{{ $sender->receiver->receiverCountry }}</td>
                                    <td>{{ $totalWeights[$sender->id] ?? '0' }} Kg</td>

                                    <td>
                                        <form id="paymentForm{{ $sender->id }}" action="{{ route('payments.store') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="sender_id" value="{{ $sender->id }}">
                                            <input type="number" name="bill_amount" class="form-control"
                                                value="{{ $sender->payments->bill_amount ?? '' }}"
                                             >
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-secondary dropdown-toggle btn-sm" type="button"
                                                id="paymentMethodDropdown{{ $key }}" data-bs-toggle="dropdown"
                                                aria-expanded="false">
                                                {{ $sender->payments->payment_method ?? 'Select Method' }}
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="paymentMethodDropdown{{ $key }}">
                                                <li><a class="dropdown-item" href="#" onclick="updatePaymentMethod(this, '{{ $key }}', '{{ $sender->id }}')">Bank Transfer</a></li>
                                                <li><a class="dropdown-item" href="#" onclick="updatePaymentMethod(this, '{{ $key }}', '{{ $sender->id }}')">Cash</a></li>
                                                <li><a class="dropdown-item" href="#" onclick="updatePaymentMethod(this, '{{ $key }}', '{{ $sender->id }}')">Both</a></li>
                                            </ul>
                                            <input type="hidden" name="payment_method" id="paymentMethod{{ $sender->id }}" value="">
                                        </div>
                                    </td>
                                    <td>
                                        <div id="amountFields{{ $sender->id }}" class="amount-fields-container">
                                            <div class="amount-field" id="cashField{{ $sender->id }}" style="{{ isset($sender->payments->cash_amount) && $sender->payments->cash_amount > 0 ? 'display: block;' : 'display: none;' }}">
                                                <small class="amount-label">Cash Amount</small>
                                                <input type="number" name="cash_amount" class="form-control amount-input"
                                                    value="{{ $sender->payments->cash_amount ?? '' }}" />
                                            </div>
                                            <div class="amount-field" id="bankField{{ $sender->id }}" style="{{ isset($sender->payments->bank_amount) && $sender->payments->bank_amount > 0 ? 'display: block;' : 'display: none;' }}">
                                                <small class="amount-label">Bank Amount</small>
                                                <input type="number" name="bank_amount" class="form-control amount-input"
                                                    value="{{ $sender->payments->bank_amount ?? '' }}" />
                                            </div>
                                        </div>
                                    </td>
                                    <td class="d-flex justify-content-center">
                                        @if($sender->payments && is_null($sender->payments->total_paid))
                                        <button type="submit" form="paymentForm{{ $sender->id }}"
                                            class="btn btn-success btn-sm mx-1" title="Save Payment">
                                            <i class="fas fa-save"></i>
                                        </button>
                                        @else
                                        <button type="submit" form="paymentForm{{ $sender->id }}"
                                            class="btn btn-success btn-sm mx-1" title="Save Payment" disabled>
                                            <i class="fas fa-save"></i>
                                        </button>
                                        @endif
                                        </form>
                                        @if ($sender->payments && !is_null($sender->payments->total_paid))
                                        <div style="display: flex; justify-content: flex-end;">
                                            <a href="#" class="btn btn-success no-print" data-bs-toggle="modal" data-bs-target="#editPaymentModal{{ $sender->payments->id }}"
                                                style="margin-left: 5px;">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </div>


                                        <!-- Modal starts here -->
                                        <div class="modal fade" id="editPaymentModal{{ $sender->payments->id }}" tabindex="-1" aria-labelledby="editPaymentModalLabel{{ $sender->payments->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form action="{{ route('payments.edit', $sender->payments->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="editPaymentModalLabel{{ $sender->payments->id }}">Edit Payment</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <input type="hidden" name="sender_id" value="{{ $sender->id }}">

                                                            <div class="mb-3">
                                                                <label class="form-label">Bill Amount</label>
                                                                <input type="number" class="form-control" name="bill_amount" value="{{ $sender->payments->bill_amount }}" readonly>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label class="form-label">Received Amount</label>
                                                                <input type="number" class="form-control" name="received_amount" value="{{ $sender->payments->total_paid }}" readonly>
                                                            </div>
                                                            @php

                                                            $remainAmount = $sender->payments->bill_amount - $sender->payments->total_paid;


                                                            $remainAmount = $remainAmount < 0 ? 0 : $remainAmount;
                                                                @endphp

                                                                <div class="mb-3">
                                                                <label class="form-label">Remain Amount</label>
                                                                <input type="number" class="form-control" name="remain_amount" value="{{ $remainAmount }}" readonly>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label">Pay By</label>
                                                            <select class="form-select" name="pay_by" id="pay_by" required onchange="handlePaymentMethod(this.value)">
                                                                <option value="" disabled selected>Select Payment Method</option>
                                                                <option value="cash">Cash</option>
                                                                <option value="bank">Bank</option>
                                                                <option value="both">Both</option>
                                                            </select>
                                                        </div>
                                                        <div class="mb-3 " id="bothPay">
                                                            <label class="form-label">Cash Amount</label>
                                                            <input type="number" class="form-control mb-2" name="pay_cash">

                                                            <label class="form-label">Bank Amount</label>
                                                            <input type="number" class="form-control" name="pay_bank">
                                                        </div>


                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-primary">Update Payment</button>
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                </div>
                                                </form>
                                            </div>
                                        </div>

                                        <!-- Modal ends -->

                                        @else
                                        <button class="btn btn-secondary btn-sm mx-1" disabled title="No payment record">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        @endif
                                      
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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

    .dropdown-menu {
        position: absolute !important;
        z-index: 1000;
    }

    .amount-fields-container {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .amount-field {
        display: flex;
        flex-direction: column;
    }

    .amount-label {
        font-size: 12px;
        color: #666;
        margin-bottom: 2px;
        text-align: left;
    }

    .amount-input {
        width: 100%;
    }
</style>






<script>
    function updatePaymentMethod(element, key, senderId) {
        const selectedMethod = element.textContent.trim();
        const button = document.getElementById(`paymentMethodDropdown${key}`);
        const paymentMethodInput = document.getElementById(`paymentMethod${senderId}`);
        const cashField = document.getElementById(`cashField${senderId}`);
        const bankField = document.getElementById(`bankField${senderId}`);

        // Update button label
        button.textContent = selectedMethod;

        // Update hidden input
        paymentMethodInput.value = selectedMethod;

        // Show/hide amount fields based on method
        if (selectedMethod === 'Cash') {
            cashField.style.display = 'block';
            bankField.style.display = 'none';
        } else if (selectedMethod === 'Bank Transfer') {
            cashField.style.display = 'none';
            bankField.style.display = 'block';
        } else if (selectedMethod === 'Both') {
            cashField.style.display = 'block';
            bankField.style.display = 'block';
        }
    }

    // Initialize datatable
</script>


@endsection