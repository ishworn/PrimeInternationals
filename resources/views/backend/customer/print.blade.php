@extends('admin.admin_master')
@section('admin')
<div class="page-content">
<a href="javascript:history.back()" class="btn btn-warning btn-rounded no-print" 
style="font-size: 15px; display: inline-flex; align-items: center; text-decoration: none; 
          background-color: #FFD700; color: black; padding: 10px 10px; border-radius: 5px; 
          margin-bottom: 15px; margin-top: 10px; margin-left: 10px;">
    <i class="fas fa-arrow-left" style="margin-right: 5px;"></i> Back
</a>


  <style>@media print { .no-print { display: none !important; } }</style>
    <div class="container-fluid">
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.1/dist/tailwind.min.css" rel="stylesheet">
        <style>
            @media print {
                body * {
                    visibility: hidden; /* Hide everything */
                }
                #invoice-print-content, #invoice-print-content * {
                    visibility: visible; /* Show only the invoice content */
                }
                #invoice-print-content {
                    position: absolute;
                    left: 0;
                    top: 0;
                    width: 100%;
                }
                .no-print {
                    display: none !important; /* Ensure elements marked with 'no-print' class are hidden */
                }
            }
        </style>

        <div class="bg-white p-8 shadow-lg">
            <div id="invoice-print-content">  <!-- This div ensures only the invoice content is printed -->
                <table id="invoice-table" class="w-full border-collapse border border-gray-300">
                    <thead>
                        <tr>
                            <th colspan="8" class="border border-gray-300 p-2 text-center bg-gray-50 font-bold text-lg">
                                INVOICE & PACKING LIST
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="3" class="border border-gray-300 p-2">
                                <div>COUNTRY OF ORIGIN: NEPAL</div>
                                <div>INVOICE DATE: {{$sender->created_at }}</div>
                                <div>INVOICE NO: {{ $invoice->number ?? 'INV-001' }}</div>
                                <div>TOTAL Box: {{ $invoice->total_boxes ?? '2' }}</div>
                            </td>
                            <td colspan="4" class="border border-gray-300 p-2">
                                @foreach($shipments as $shipment)
                                <div>SHIPMENT VIA: {{ $shipment->shipment_via  }}</div>
                                <div>ACTUAL WEIGHT: {{ $shipment->actual_weight }}</div>
                                <div>Dimension: {{ $shipment->dimension }}</div>
                                @endforeach
                            </td>
                        </tr>
                        <!-- Shipper Details -->
                        <tr>
                            <td colspan="3" class="border border-gray-300 p-2">
                                <div class="font-bold">SHIPPER</div>
                                <div>OM X. GLOBAL PVT. LTD. (TRADE NAME- PRIME GORKHA SER</div>
                                <div>Sender Name : {{ $sender->senderName }}</div>
                                <div>PAN NO: 615794828</div>
                                <div>Aloknagor-310 Kathmandu</div>
                                <div>Email: werep@primegorkha.com</div>
                                <div>Phone: +977 9708072372</div>
                            </td>
                            <td colspan="4" class="border border-gray-300 p-2">
                                <div class="font-bold">CONSIGNEE</div>
                                @foreach($receivers as $receiver)
                                <div>Name: {{ $receiver->receiverName }}</div> 
                                <div>Phone: {{ $receiver->receiverPhone  }}</div>
                                <div>Email: {{ $receiver->receiverEmail  }}</div>
                                <div>Complete Address: {{ $receiver->receiverAddress  }}</div>
                                @endforeach
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Print Button (Hidden in print mode) -->
        <button onclick="printInvoice()" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded no-print">
            Print Invoice
        </button>

    </div>
</div>

<script>
    function printInvoice() {
        window.print();
    }
</script>
@endsection
