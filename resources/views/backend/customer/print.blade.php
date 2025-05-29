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

    <style>
        @media print {
            .no-print {
                display: none !important;
            }
        }
    </style>
    <div class="container-fluid">
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.1/dist/tailwind.min.css" rel="stylesheet">
        <style>
            @media print {
                body * {
                    visibility: hidden;
                    /* Hide everything */
                }

                #invoice-print-content,
                #invoice-print-content * {
                    visibility: visible;
                    /* Show only the invoice content */
                }

                #invoice-print-content {
                    position: absolute;
                    left: 0;
                    top: 0;
                    width: 100%;
                }

                .no-print {
                    display: none !important;
                    /* Ensure elements marked with 'no-print' class are hidden */
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
                            <th colspan="3" class="border-[1px] border-black p-2 text-center font-bold text-lg" style="background-color: rgb(106, 13, 173);">
                                <img src="{{ asset('logo/prime-without.png') }}" alt="Logo" class="mx-auto w-24 h-auto">
                            </th>

                            <th colspan="3" class="border-[1px] border-black p-2 text-center bg-gray-50 font-bold text-lg">
                                PRIME GURKHA LOGISTICS PVT. LTD.
                            </th>
                            <th colspan="8" class="border-[1px] border-black p-2 text-center bg-gray-50 font-bold text-lg">
                                AWB Number ({{ $box->box_number }})<br>Bar Code
                            </th>

                        </tr>
                    </thead>
                    <tbody>
                        
                        <tr class="text-center">
                            <td colspan="3" class="border-[1px] border-black p-2 text-black font-bold">ACCOUNT NUMBER</td>
                            <td colspan="3" class="border-[1px] border-black p-2 text- font-bold">DESTINATION</td>
                            <td colspan="4" class="border-[1px] border-black p-2 text-black font-bold">FORWARDING NO.</td>
                            <td colspan="4" rowspan="2" class="border-[1px] border-black p-2 text-black text-center font-bold">TOTAL BOXES
                                <div >{{ $sender->boxes->count() }}</div>
                            </td>
                        </tr>
                        <tr >
                            <td colspan="3" class="border-[1px] border-black p-2 text-black text-center">ACCOUNT NUMBER</td>
                            <td colspan="3" class="border-[1px] border-black p-2 text-black text-center">
                                @foreach ($receivers as $receiver)
                                <div class="font-bold text-xl">{{$receiver->receiverCountry }} </div>

                                @endforeach
                            </td>
                            <td colspan="5" class="border-[1px] border-black p-2 text-black">FORWARDING NO.</td>
                            
                        </tr>
                        <tr class="border-[1px] border-black p-2 text-black font-bold">
                            <td colspan="4" rowspan="5" class="border-[1px] border-black p-2 text-black">
                                <!-- Shipper Details -->
                            
                                <div class="font-bold">SHIPPER</div>
                                <div>OM X. GLOBAL PVT. LTD. (TRADE NAME- PRIME GURKHA LOGISTICS)</div>
                                <div>PAN NO: 619794828</div>
                                <div>Aloknagor-310 Kathmandu</div>
                                <div>Phone: +977 9708072372</div>
                                <div>Email: primegurkha@gmail.com</div>
                            </td>
                            <td colspan="6" rowspan="5" class="border-[1px] border-black p-2 text-black font-bold">
                                <div class="font-bold">CONSIGNEE</div>
                                @foreach ($receivers as $receiver)
                                <div>Name: {{ $receiver->receiverName }}</div>
                                <div>Phone: {{ $receiver->receiverPhone }}</div>
                                <div>Email: {{ $receiver->receiverEmail }}</div>
                                <div>Postal Code: {{ $receiver->receiverPostalcode }}</div>
                                <div>Complete Address: {{ $receiver->receiverAddress }}</div>
                                @endforeach
                            </td>
                            <td colspan="8" class="border-[1px] border-black p-2 text-black">
                                <div class="font-bold">ACTUAL WEIGHT</div>
                                 @foreach ($shipments as $shipment)
                                  <div class="font-bold">{{$shipment->actual_weight }} </div>

                                 @endforeach
                            </td>
                        </tr>
                        <tr class="border-[1px] border-black p-2 text-black">
                            <td class="font-bold">CHARGEABLE WEIGHT </td>
                        </tr>
                        <tr class="border-[1px] border-black p-2 text-black">
                            <td class="font-bold">VOLUMETRIC WEIGHT</td>
                        </tr>
                        <tr class="border-[1px] border-black p-2 text-black">
                            <td class="font-bold">SHIPMENT VALUE</td>
                        </tr>
                        <tr class="border-[1px] border-black p-2 text-black">
                            <td class="font-bold">PAYMENT METHOD</td>
                           
                        </tr>
                        <tr class="border-[1px] border-black p-2 text-black text-center">
                            <td rowspan="2" colspan="2" class="border-[1px] border-black p-2 text-black  ">
                               <div class="font-bold"> DESCRIPTION OF GOODS</div>
                            </td>
                            <td rowspan="2" colspan="2" class="border-[1px] border-black p-2 text-black text-center">
                                PARCEL
                            <div class="font-bold">SELF</div>

                            </td>
                            <td rowspan="2" colspan="2" class="border-[1px] border-black p-2 text-black text-center">
                                <div class="font-bold">BOOKING DATE</div>
                            </td>
                            <td rowspan="2" colspan="2" class="border-[1px] border-black p-2 text-black text-center">
                                <div class="font-bold">INSURANCE</div>
                                <div class="font-bold ">YES <i class="far fa-square pr-2"></i>
                                NO <i class="far fa-square"></i>
                            </div>
                            </td>
                        </tr>
                        <tr  class="border-[1px] border-black p-2 text-black">
                            <td colspan="16" >
                            <!-- <div>DIMENSION: {{ $box->box_dimension ?? 'N/A' }}</div> -->
                            </td>
                        </tr>
                        
                        <tr>
                            <td colspan="4" class="border-[1px] border-black p-2 align-top text-black">
                                <div class="font-bold">SHIPPER AGREEMENT</div>
                                <div>SHIPPER AGREES TO PRIME GURKHA LOGISTICS PVT. LTD.</div>
                                <div>STANDARD TERMS AND CONDITIONS OF CARRAGE</div>
                                <div>SHIPPER'S SIGNATURE</div>
                                <div>DATE</div>
                                <div>BOOKING DATE:</div>
                            </td>
                            <td colspan="4" class="border-[1px] border-black p-2 align-top text-black">
                                <div class="font-bold">AWB NUMBER</div>

                            </td>
                            <td colspan="4" class="border-[1px] border-black p-2 align-top text-black">
                                <div class="font-bold">RECEIVED IN GOOD CONDITION</div>
                                <div class="font-bold">NAME</div>
                                <div class="font-bold">SIGN</div>

                            </td>
                            
                            
                            <!-- <td colspan="4" class="border-[1px] border-black p-2 align-top text-black">
                                <div>INVOICE DATE: {{ $sender->created_at->format('Y-m-d') }}</div>
                                <div>INVOICE NO: {{ $sender->invoiceId ?? 'INV-001' }}</div>
                                <div>TOTAL BOXES: {{ $sender->boxes->count() }}</div>
                            </td> -->
                            
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

<!-- <script>
    function printInvoice() {
        window.print();
    }
</script> -->

<!-- <script>
    window.onload = () => window.print();
    window.onafterprint = () => {
            window.history.back(); // go back to previous page
        };
</script> -->
<script>
    function printInvoice() {
        window.print();
    }

    // window.onload = () => {
    //     // Check if URL has ?autoPrint=1 query param
    //     const urlParams = new URLSearchParams(window.location.search);
    //     if (urlParams.get('autoPrint') === '1') {
    //         printInvoice();
    //     }
    // }
</script>



@endsection