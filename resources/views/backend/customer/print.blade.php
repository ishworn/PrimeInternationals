@extends('admin.admin_master')
@section('admin')
<script src="https://cdn.tailwindcss.com"></script>
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

        <!-- Wrap all invoices in a single div for printing -->
        <div id="invoice-print-content"> <!-- This div ensures all the invoices are printed -->
            @foreach ($sender->boxes as $box)
            <div class="bg-white p-8 shadow-lg mb-4">
                <table id="invoice-table" class="w-full border-[1px] border-black text-black">
                    <thead>
                        <tr>
                            <th colspan="8" class="border-[1px] border-black p-2 text-center bg-gray-50 font-bold text-lg">
                                PRIME GURKHA LOGISTICS PVT. LTD. ({{ $box->box_number }})
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Shipper Details -->
                       <tr>
                            <td colspan="4" class="border-[1px] border-black p-2 text-black">
                                <div class="font-bold">SHIPPER</div>
                                <div>OM X. GLOBAL PVT. LTD. (TRADE NAME- PRIME GURKHA LOGISTICS)</div>
                                <div>PAN NO: 619794828</div>
                                <div>Aloknagor-310 Kathmandu</div>
                                <div>Phone: +977 9708072372</div>
                                <div>Email: primegurkha@gmail.com</div>
                            </td>

                            <td colspan="4" class="border-[1px] border-black p-2 align-top text-black">
                                
                            @foreach ($receivers as $receiver)
                    <div>DESTINATION COUNTRY: {{$receiver->receiverCountry }} </div>
                                  
                                @endforeach

                               
                                <div>INVOICE DATE: {{ $sender->created_at->format('Y-m-d') }}</div>
                                <div>INVOICE NO: {{ $sender->invoiceId ?? 'INV-001' }}</div>
                                <div>TOTAL BOXES: {{ $sender->boxes->count() }}</div>
                            </td>
                        </tr>

                        <!-- Consignee Details -->
                        <tr>
                            <td colspan="4" class="border-[1px] border-black p-2 text-black">
                                <div class="font-bold">CONSIGNEE</div>
                                @foreach ($receivers as $receiver)
                                    <div>Name: {{ $receiver->receiverName }}</div>
                                    <div>Phone: {{ $receiver->receiverPhone }}</div>
                                    <div>Email: {{ $receiver->receiverEmail }}</div>
                                    <div>Complete Address: {{ $receiver->receiverAddress }}</div>
                                @endforeach
                            </td>

                            <!-- Shipment Details -->
                            <td colspan="4" class="border-[1px] border-black p-2 text-black">
                                @foreach ($shipments as $shipment)
                                    <div>SHIPMENT VIA: {{ $shipment->shipment_via }}</div>
                                    <div>DIMENSION: {{ $shipment->dimension }}</div>
                                       
                                @endforeach
                                    <div>Box Weight: {{ $box->box_weight }} Kg</div> <!-- Using box weight here -->
                                
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            @endforeach
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
