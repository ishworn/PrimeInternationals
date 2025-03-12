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

        <div class="bg-white p-8 shadow-lg">
            <div id="invoice-print-content">  <!-- This div ensures only the invoice content is printed -->
                <table id="invoice-table" class="w-full border-[1px] border-black text-black">
                    <thead>
                    <tr>
                                <td colspan="4" class="border-[1px] border-black p-2 text-black">
                                    <div class="font-bold">SHIPPER</div>
                                    <div>OM X. GLOBAL PVT. LTD. (TRADE NAME- PRIME GORKHA LOGISTICS)</div>
                                    <div>PAN NO: 619794828</div>
                                    <div>Aloknagor-310 Kathmandu</div>
                                    <div>Phone: +977 9708072372</div>
                                    <div>Email:primegurkha@gmail.com</div>
                                    
                                   
                                </td>

                                <td colspan="4" class="border-[1px] border-black p-2 align-top text-black">
                                    <div>COUNTRY OF ORIGIN: NEPAL</div>
                                    <div>INVOICE DATE: {{$sender->created_at }}</div>
                                    <div>INVOICE NO: {{ $sender->invoiceId ?? 'INV-001' }}</div>
                                    <div>TOTAL BOXES: {{ $totalBoxes ?? '0' }}</div>
                                </td>
                            </tr>
                        <!-- Shipper Details -->
                        <tr>
                                <td colspan="4" class="border-[1px] border-black align-top p-2 text-black">
                                    <div class="font-bold">CONSIGNEE</div>
                                    @foreach($receivers as $receiver)
                                    <div>Name: {{ $receiver->receiverName }}</div>
                                    <div>Phone: {{ $receiver->receiverPhone  }}</div>
                                    <div>Email: {{ $receiver->receiverEmail  }}</div>
                                    <div>Complete Address: {{ $receiver->receiverAddress  }}</div>
                                    @endforeach
                                </td>

                                <td colspan="4" class="border-[1px] border-black align-top p-2 text-black">
                                    @foreach($shipments as $shipment)
                                    <div>SHIPMENT VIA: {{ $shipment->shipment_via  }}</div>
                                    <div>ACTUAL WEIGHT: {{ $shipment->actual_weight }}</div>
                                    <div>DIMENSION: {{ $shipment->dimension }}</div>
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
