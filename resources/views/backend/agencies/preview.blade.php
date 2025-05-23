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
    <div class="container-fluid">
        <!-- Start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0" style="font-size: 24px; font-weight: bold;">Agencies List</h4>
                </div>
            </div>
        </div>

       



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
                                <div class="col-md-4 d-flex align-items-end">
                                    <button id="clearFilters" class="btn btn-secondary mt-2">Clear Filters</button>
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
                                     
                                        <th class="dispatch-date" style=" display: none;">Dispatch Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($senders as $key => $sender)
                                    <tr>
                                        <td>{{ $sender->invoiceId}}</td>
                                        <td>{{ $sender->receiver->receiverName }}</td>
                                        <td>{{ $sender->receiver->receiverCountry ?? 'N/A' }}</td>
                                        <td>{{ $sender->dispatch->dispatch_by ?? 'N/A' }}</td>
                                        <td>{{ $sender->shipments->actual_weight ?? 'N/A' }}</td>
                                        <td>{{ number_format($sender->payments->bill_amount ?? 0, 2) }}</td>
                                        <td>{{ number_format($sender->payments->total_paid ?? 0, 2) }}</td>
                                       
                                        <td class="dispatch-date" style="display: none;">{{ $sender->created_at ?? '' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-light">
                                    <tr>
                                        <td colspan="5"><strong>Total</strong></td>
                                        <td><strong id="totalBill">0.00</strong></td>
                                        <td><strong id="totalReceived">0.00</strong></td>
                                      
                                    </tr>
                                </tfoot>
                            </table>
                        </div> <!-- End Table -->
                    </div>
                </div>
            </div>
        </div>


        <!-- End page title -->


    </div> <!-- container-fluid -->
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.tailwindcss.com"></script>
<script>
    $(document).ready(function() {
        function calculateTotals() {
            let totalBill = 0;
            let totalReceived = 0;
            let totalPaidToAgencies = 0;

            $("#datatabless tbody tr:visible").each(function() {
                const bill = parseFloat($(this).find("td:nth-child(6)").text().replace(/,/g, '').trim()) || 0;
                const received = parseFloat($(this).find("td:nth-child(7)").text().replace(/,/g, '').trim()) || 0;
                const paid = parseFloat($(this).find("td:nth-child(8)").text().replace(/,/g, '').trim()) || 0;

                totalBill += bill;
                totalReceived += received;
                totalPaidToAgencies += paid;
            });

            $("#totalBill").text(totalBill.toFixed(2));
            $("#totalReceived").text(totalReceived.toFixed(2));
            $("#totalPaidToAgencies").text(totalPaidToAgencies.toFixed(2));
        }

        function filterTable() {
            const fromDate = $("#fromDate").val();
            const toDate = $("#toDate").val();

            $("#datatabless tbody tr").each(function() {
                const rowDateStr = $(this).find(".dispatch-date").text().trim();
                const rowDate = rowDateStr ? new Date(rowDateStr) : null;

                if (!rowDate || isNaN(rowDate.getTime())) {
                    $(this).hide();
                    return;
                }

                let showRow = true;
                if (fromDate) showRow = showRow && rowDate >= new Date(fromDate);
                if (toDate) showRow = showRow && rowDate <= new Date(toDate);

                $(this).toggle(showRow);
            });

            calculateTotals();
        }

        $("#fromDate, #toDate").on("change", filterTable);

        // Clear filters and show all rows
        $("#clearFilters").on("click", function() {
            $("#fromDate").val('');
            $("#toDate").val('');
            $("#datatabless tbody tr").show();
            calculateTotals();
        });

        // Initial total calculation
        calculateTotals();
    });
</script>






@endsection