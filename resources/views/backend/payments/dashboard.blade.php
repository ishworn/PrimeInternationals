@extends('admin.admin_master')

@section('admin')

<!-- Page Content -->
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
        <!-- Page Title -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 text-xl font-bold">Payment Details</h4>
                </div>
            </div>
        </div>

        <!-- Cards Section -->
        <div class="row mb-3">
            <div class="col-md-4">
                <div class="card text-white bg-primary">
                    <div class="card-body">
                        <h5 class=" p-0  m-1text-2xl font-semibold">Today's Income Summary</h5>
                        <p class=" m-1 text-lg">Cash: <strong>{{ number_format($totalcash, 2) }}</strong></p>
                        <p class=" m-1 text-lg">Bank Transfer: <strong>{{ number_format($totalBank_transfer, 2) }}</strong></p>
                        <p class=" m-1 text-xl font-bold">Grand Total: {{ number_format($totalcash + $totalBank_transfer, 2) }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-success">
                    <div class="card-body">
                        <h5 class="m-1">Weekly Income ( <small>
                                {{ $weekRange }}
                            </small>)</h5>
                        <p class="m-1">Cash: {{ number_format($weeklyCash, 2) }}</p>
                        <p class="m-1">Bank: {{ number_format($weeklyBankTransfer, 2) }}</p>
                        <p class="m-1 text-xl font-bold "> Grand Total: {{ number_format($weeklyTotal, 2) }} </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-warning">
                    <div class="card-body">
                        <h5 class="m-1">Monthly Income ( <small>
                                {{ $monthRange }}
                            </small>)</h5>
                        <p class="m-1">Cash: {{ number_format($monthlyCash, 2) }}</p>
                        <p class="m-1">Bank: {{ number_format($monthlyBankTransfer, 2) }}</p>
                        <p class="m-1 text-xl font-bold "> Grand Total: {{ number_format($monthlyTotal, 2) }} </p>
                    </div>
                </div>
            </div>
        </div>
        @php
        $totalBill = 0;
        $totalReceived = 0;
        $totalPaidToAgencies = 0;

        foreach ($sendersAll as $sender) {
        $totalBill += $sender->payments->bill_amount ?? 0;
        $totalReceived += $sender->payments->total_paid ?? 0;
        $totalPaidToAgencies += $sender->payments->debits ?? 0;


        }
        @endphp

        <!-- Display Totals on Top -->
        <div class="row mb-3">

            <div class="col-md-4">
                <div class="card text-white bg-danger">
                    <div class="card-body">
                        <h5 class="card-title">Expenses</h5>
                        <p class="card-text m-1" style="font-size: 14px; font-weight: bold;">
                            CashPaid: {{ number_format($totalCashPaid, 2) }}
                        </p>
                        <p class="card-text m-1" style="  font-size: 14px; font-weight: bold;">
                            BankPaid: {{ number_format($totalBankPaid, 2) }}
                        </p>
                        <p class="card-text m-1" style="  font-size: 14px; font-weight: bold;">
                            Total Expenses : {{ number_format($totalExpenses, 2) }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-primary">
                    <div class="card-body">
                        <h5 class="card-title">Total Received Amount</h5>
                        <p class="card-text m-1" style="font-size: 14px; font-weight: bold;">
                            Cash Receive : {{ number_format($cash_receive, 2) }}
                        </p>
                        <p class="card-text m-1" style="  font-size: 14px; font-weight: bold;">
                            Bank Receive: {{ number_format($bank_receive, 2) }}
                        </p>
                        <p class="card-text m-1" style="  font-size: 14px; font-weight: bold;">
                            Total: {{ number_format($totalReceived, 2) }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-danger">
                    <div class="card-body">
                        <h5 class="card-title">Total Paid to Agencies</h5>
                        <p class="card-text m-1" style="font-size: 14px; font-weight: bold;">
                            Cash : {{ number_format($cashdebt, 2) }}
                        </p>
                        <p class="card-text m-1" style="font-size: 14px; font-weight: bold;">
                            Bank: {{ number_format($bankdebt, 2) }}
                        </p>
                        <p class="card-text m-1" style="font-size: 14px; font-weight: bold;">
                            Total : {{ number_format($debits, 2) }}
                        </p>
                    </div>
                </div>
            </div>


        </div>
        <div class="row mb-3">
        <div class="col-md-4">
                <div class="card text-white bg-warning">
                    <div class="card-body">
                        <h5 class="card-title">Opening Balance {{ \Carbon\Carbon::parse($date)->format('F j, Y') }}</h5>
                        <p class="card-text m-1" style="font-size: 14px; font-weight: bold;">
                            Cash: {{ number_format($yesterdayClosingCash, 2) }}
                        </p>
                        <p class="card-text m-1" style="font-size: 14px; font-weight: bold;">
                            Bank: {{ number_format($yesterdayClosingBank, 2) }}
                        </p>
                        <p class="card-text m-1" style="font-size: 14px; font-weight: bold;">
                            Total : {{ number_format($yesterdayClosingBalance, 2) }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card text-white bg-success">
                    <div class="card-body">
                        <h5 class="card-title">Running Balance {{ \Carbon\Carbon::parse($date)->format('F j, Y') }}</h5>
                        <p class="card-text m-1" style="font-size: 14px; font-weight: bold;">
                            Cash: {{ number_format($openingBalanceCash, 2) }}
                        </p>
                        <p class="card-text m-1" style="font-size: 14px; font-weight: bold;">
                            Bank: {{ number_format($openingBalanceBank, 2) }}
                        </p>
                        <p class="card-text m-1" style="font-size: 14px; font-weight: bold;">
                            Total : {{ number_format($openingBalance, 2) }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-primary">
                    <div class="card-body">
                        <h5 class="card-title">Total Bill Amount</h5>
                        <p class="card-text" style="font-size: 20px; font-weight: bold;">
                            {{ number_format($totalBill, 2) }}
                        </p>
                    </div>
                </div>
            </div>



        </div>




        <table id="datatable" class="table table-bordered table-striped " style="width: 100%; margin-top: 50px;">
            <thead class="thead-dark">
                <tr>
                    <th>Agency Name</th>
                    <th>Total Weight </th>
                    <th class="text-right">Total Received</th>
                    <th class="text-right">Total Debits</th>
                    <th class="text-right">Profit</th>
                    <th class="text-right">Profit Margin</th>
                    <th class="text-right">Total Payments</th>
                </tr>
            </thead>
            <tbody>
                @foreach($agencyPayments as $agency)
                <tr>
                    <td>{{ $agency->agency_name }}</td>
                    <td class="text-right">{{ number_format($agency->total_weight, 2) }} kg</td>

                    <td class="text-right">{{ number_format($agency->total_received, 2) }}</td>
                    <td class="text-right">{{ number_format($agency->total_debits, 2) }}</td>
                    <td class="text-right font-weight-bold 
                                {{ $agency->profit >= 0 ? 'text-success' : 'text-danger' }}">
                        {{ number_format($agency->profit, 2) }}
                    </td>
                    <td class="text-right">{{ $agency->profit_margin }}%</td>
                    <td class="text-right">{{ $agency->payment_count }}</td>
                </tr>
                @endforeach
                @if($agencyPayments->isNotEmpty())
                <tr class="font-weight-bold bg-light">
                    <td>Totals</td>
                    <td class="text-right">{{ number_format($agencyPayments->sum('total_weight'), 2) }} kg</td>
                    <td class="text-right">{{ number_format($agencyPayments->sum('total_received'), 2) }}</td>
                    <td class="text-right">{{ number_format($agencyPayments->sum('total_debits'), 2) }}</td>
                    <td class="text-right 
                                {{ $agencyPayments->sum('profit') >= 0 ? 'text-success' : 'text-danger' }}">
                        {{ number_format($agencyPayments->sum('profit'), 2) }}
                    </td>
                    <td class="text-right">
                        @php
                        $totalReceived = $agencyPayments->sum('total_received');
                        $totalProfit = $agencyPayments->sum('profit');
                        $margin = $totalReceived > 0 ? ($totalProfit / $totalReceived) * 100 : 0;
                        @endphp
                        {{ number_format($margin, 2) }}%
                    </td>
                    <td class="text-right">{{ $agencyPayments->sum('payment_count') }}</td>
                </tr>
                @endif
            </tbody>
        </table>



        <!-- Data Table -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-lg">
                    <div class="card-body">
                        <!-- Filters -->
                        <div class="m">
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="fromDate">From Date:</label>
                                    <input type="date" id="fromDate" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label for="toDate">To Date:</label>
                                    <input type="date" id="toDate" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label for="filterDispatch">Filter by Dispatch To:</label>
                                    <select id="filterDispatch" class="form-control">
                                        <option value="">All</option>
                                        @foreach($sendersAll as $sender)
                                        <option value="{{ $sender->dispatch->dispatch_by ?? 'N/A' }}">{{ $sender->dispatch->dispatch_by ?? 'N/A' }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Data Table -->
                            <table id="datatabless" class="table table-striped table-bordered dt-responsive nowrap" style="width: 100%; margin-top: 50px;">
                                <thead class="bg-primary text-white">
                                    <tr>
                                        <th>Sl</th>
                                        <th>Receiver Name</th>
                                        <th>Country</th>
                                        <th>Dispatch To</th>
                                        <th>Total Weight</th>
                                        <th style="width: 130px;">Bill Amount</th>
                                        <th style="width: 130px;">Receive Amount</th>
                                        <th style="width: 130px;">Pay To Agencies</th>
                                        <th class="dispatch-date" style=" display: none;">Dispatch Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($sendersAll as $key => $sender)
                                    <tr>
                                        <td>{{ $sender->invoiceId}}</td>
                                        <td>{{ $sender->receiver->receiverName }}</td>
                                        <td>{{ $sender->receiver->receiverCountry ?? 'N/A' }}</td>
                                        <td>{{ $sender->dispatch->dispatch_by ?? 'N/A' }}</td>
                                        <td>{{ $sender->shipments->actual_weight ?? 'N/A' }}</td>
                                        <td>{{ number_format($sender->payments->bill_amount ?? 0, 2) }}</td>
                                        <td>{{ number_format($sender->payments->total_paid ?? 0, 2) }}</td>
                                        <td>{{ number_format($sender->payments->debits ?? 0, 2) }}</td>
                                        <td class="dispatch-date" style="display: none;">{{ $sender->created_at ?? '' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-light">
                                    <tr>
                                        <td colspan="5"><strong>Total</strong></td>
                                        <td><strong id="totalBill">0.00</strong></td>
                                        <td><strong id="totalReceived">0.00</strong></td>
                                        <td><strong id="totalPaidToAgencies">0.00</strong></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div> <!-- End Table -->
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Function to calculate the totals for the filtered rows
        function calculateTotals() {
            var totalBill = 0;
            var totalReceived = 0;
            var totalPaidToAgencies = 0;

            // Loop through each visible row in the table after filtering
            $("#datatabless tbody tr:visible").each(function() {
                var row = $(this);

                // Parse each value, ensuring it's a number (in case of formatting issues)
                var billAmount = parseFloat(row.find("td:nth-child(6)").text().replace(/,/g, '').trim()) || 0;
                var receivedAmount = parseFloat(row.find("td:nth-child(7)").text().replace(/,/g, '').trim()) || 0;
                var paidToAgencies = parseFloat(row.find("td:nth-child(8)").text().replace(/,/g, '').trim()) || 0;

                // Debugging: Log the parsed values for each row
                console.log('Row Bill:', billAmount, 'Received:', receivedAmount, 'Paid to Agencies:', paidToAgencies);

                // Accumulate the totals
                totalBill += billAmount;
                totalReceived += receivedAmount;
                totalPaidToAgencies += paidToAgencies;
            });

            // Update the footer with the calculated totals
            $("#totalBill").text(totalBill.toFixed(2));
            $("#totalReceived").text(totalReceived.toFixed(2));
            $("#totalPaidToAgencies").text(totalPaidToAgencies.toFixed(2));

            // Debugging: Log the final totals
            console.log('Total Bill:', totalBill, 'Total Received:', totalReceived, 'Total Paid to Agencies:', totalPaidToAgencies);
        }

        // Function to filter the table rows based on the selected filters
        function filterTable() {
            var fromDate = $("#fromDate").val();
            var toDate = $("#toDate").val();
            var selectedDispatch = $("#filterDispatch").val().toLowerCase();

            $("#datatabless tbody tr").each(function() {
                var row = $(this);
                var rowDate = row.find(".dispatch-date").text().trim();
                var rowDispatch = row.find("td:nth-child(4)").text().trim().toLowerCase();

                // Check if rowDate is empty and handle missing values
                if (!rowDate) {
                    row.hide();
                    return;
                }

                // Convert rowDate to a valid format for comparison
                var rowDateObj = new Date(rowDate);
                if (isNaN(rowDateObj.getTime())) {
                    console.log("Invalid Date Found:", rowDate); // Debugging
                    row.hide();
                    return;
                }

                var fromDateObj = fromDate ? new Date(fromDate) : null;
                var toDateObj = toDate ? new Date(toDate) : null;

                var dateMatch = true;
                if (fromDateObj && !toDateObj) {
                    dateMatch = rowDateObj >= fromDateObj;
                } else if (!fromDateObj && toDateObj) {
                    dateMatch = rowDateObj <= toDateObj;
                } else if (fromDateObj && toDateObj) {
                    dateMatch = (rowDateObj >= fromDateObj && rowDateObj <= toDateObj);
                }

                var dispatchMatch = (selectedDispatch === "" || rowDispatch === selectedDispatch);

                if (dateMatch && dispatchMatch) {
                    row.show();
                } else {
                    row.hide();
                }
            });

            // Call calculateTotals after filtering
            calculateTotals();
        }

        // Trigger filterTable when user changes filters (date or dispatch)
        $("#fromDate, #toDate, #filterDispatch").on("change", filterTable);

        // Call calculateTotals on page load to show totals without any filters applied
        calculateTotals();
    });
</script>


@endsection