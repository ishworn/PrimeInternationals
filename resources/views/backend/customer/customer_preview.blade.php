@extends('admin.admin_master')
@section('admin')
<style>
    @media print {
        .no-print {
            display: none !important;
        }
    }

    @media print {
        @page {
            size: A4;
        }

        body {
            print-color-adjust: exact;
            -webkit-print-color-adjust: exact;
        }

        .print-hidden {
            display: none !important;
        }

        .shadow-lg {
            box-shadow: none !important;
        }
    }
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.4/xlsx.full.min.js"></script>
<div class="page-content">
    <div class="container-fluid">
        <a href="javascript:history.back()" class="btn btn-warning btn-rounded no-print"
            style="font-size: 15px; display: inline-flex; align-items: center; text-decoration: none; 
          background-color: #FFD700; color: black; padding: 10px 10px; border-radius: 5px; 
          margin-bottom: 15px; margin-top: 10px; margin-left: 20px;">
            <i class="fas fa-arrow-left" style="margin-right: 5px;"></i> Back
        </a>
        <div class="min-h-screen p-8">
            <!-- Print/Download buttons -->
            <div class="print-hidden mb-4 flex gap-4 justify-end">
                <button onclick="window.print()" class="flex items-center gap-2 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    Print
                </button>
                <a href="{{ route('export.excel', $sender->id) }}" class="flex items-center gap-2 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    Export to Excel
                </a>
                <a href="{{ route('invoice.print', $sender->id) }}" class="flex items-center gap-2 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    Print Invoice
                </a>
            </div>
            <!-- Invoice Content -->
            <div class="bg-white p-8 shadow-lg">
                <table id="invoice-table" class="w-full border-collapse border-[1px] border-black text-black">
                    <div id="invoice-content">
                        <thead>
                            <tr>
                                <th colspan="8" class="border-[1px] border-black p-2 text-center bg-gray-50 font-bold text-lg">
                                    INVOICE & PACKING LIST
                                </th>
                            </tr>
                        </thead>
                        <tbody>
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
                    </div>
                    <div>
                        <table class="table-auto w-full border-collapse">
                            <thead>
                                <tr class="bg-gray-50 text-black">
                                    <th class="border-[1px] border-black p-2">BOXES</th>
                                    <th class="border-[1px] border-black p-1">SR NO</th>
                                    <th class="border-[1px] border-black p-2">DESCRIPTION</th>
                                    <th class="border-[1px] border-black p-2">HS CODE</th>
                                    <th class="border-[1px] border-black p-2">QUANTITY</th>
                                    <th class="border-[1px] border-black p-2">UNIT RATE (US$)</th>
                                    <th class="border-[1px] border-black p-2">AMOUNT (US$)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sender->boxes as $box)
                                <tr>
                                    <td rowspan="{{ count($box->items) + 1 }}" class=" text-black border-[1px] border-black p-2 text-center font-bold">
                                        {{ $box->box_number }}
                                    </td>
                                </tr>
                                @foreach($box->items as $index => $item)
                                <tr class="text-black" >
                                    <td class="border-[1px] border-black p-2">{{ $index + 1 }}</td>
                                    <td class="border-[1px] border-black p-1">{{ $item->item }}</td>
                                    <td class="border-[1px] border-black p-2">{{ $item->hs_code }}</td>
                                    <td class="border-[1px] border-black p-2">{{ $item->quantity }}</td>
                                    <td class="border-[1px] border-black p-2 text-right">{{ '$' . number_format($item->unit_rate, 2) }}</td>
                                    <td class="border-[1px] border-black p-2 text-right">{{ '$' . number_format($item->amount, 2) }}</td>
                                </tr>
                                @endforeach
                                @endforeach
                                <!-- Totals -->
                                <tr class="text-black">
                                    <td colspan="2" class="border-[1px] border-black p-2 text-right font-bold"></td>
                                    <td colspan="2" class="border-[1px] border-black p-2 text-right font-bold">Total Quantity</td>
                                    <td class="border-[1px] border-black p-2">{{ $totalQuantity ?? '18' }}</td>
                                    <td colspan="1" class="border-[1px] border-black p-2 font-bold">Grand total</td>
                                    <td class="border-[1px] border-black p-2 font-bold text-right"> $ {{$grandTotal ?? 'N/A'}}</td>
                                </tr>
                             
                                <tr class="text-black" >
                                    <td colspan="3" class="border-[1px] border-black p-2">
                                        <div class="font-bold">NOTES</div>
                                        <div>We declare that the above mentioned goods are made in Nepal and other descriptions are true.</div>
                                    </td>
                                    <td colspan="4" flex flex justify-between class="border-[1px] border-black p-2">
                                            <div class="font-bold flex justify-between items-center">
                                                <div>SIGNATURE</div>
                                                <span class="font-bold text-right">STAMP</span>
                                            </div>
                                            <div class="h-20"></div>
                                        </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>  
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
