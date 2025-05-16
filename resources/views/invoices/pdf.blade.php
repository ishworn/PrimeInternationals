<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Invoice</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <div id="printSection">
        <div class="max-w-4xl mx-auto bg-white border border-gray-200 p-6 sm:p-10 shadow-md mt-6">
            <table id="invoice-table" class="w-full  text-black">
                <thead>
                    <tr>
                        <th colspan="8" class=" p-2 text-center bg-gray-50 font-bold text-lg">
                            PRIME GURKHA LOGISTICS PVT. LTD.
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Shipper Details -->
                    <tr>
                        <td colspan="2" class="  p-2 text-black">
                            <div>Aloknagor-310 Kathmandu</div>
                            <div>Phone: +977 9708072372</div>
                            <div>Invoice No: {{ $sender->invoiceId ?? 'INV-001' }}</div>
                        </td>
                        <td colspan="2" class=" p-2 align-top text-black">
                            <div>Destination Country: {{$sender->receiver->receiverCountry }} </div>
                            <div>Invoice Date: {{ $sender->created_at->format('Y-m-d') }}</div>
                            <div>Total Boxes: {{ $sender->boxes->count() }}</div>
                        </td>
                    </tr>
                    <!-- Consignee Details -->
                    <tr>
                        <td colspan="2" class=" p-2 text-black">
                            <div>Name : {{ $sender->senderName  ?? 'N/A' }}</div>
                            <div>Email: {{ $sender->senderEmail }}</div>
                            <div>Phone No : {{ $sender->senderPhone }}</div>
                        </td>
                        <!-- Shipment Details -->
                        <td colspan="2" class=" p-2 text-black">
                            <div>Name: {{ $sender->receiver->receiverName }}</div>
                            <div>Phone: {{ $sender->receiver->receiverPhone }}</div>
                            <div>Email: {{ $sender->receiver->receiverEmail }}</div>
                            <div>Postal Code: {{ $sender->receiver->receiverPostalcode }}</div>
                            <div>Complete Address: {{ $sender->receiver->receiverAddress }}</div>
                        </td>
                    </tr>
                </tbody>
            </table>


            <div class="mt-8 overflow-x-auto">
                <table class="w-full text-left min-w-[600px]">
                    <thead>
                        <tr class="bg-gray-100 text-gray-700 uppercase text-xs">
                            <th class="py-3 px-2">Description</th>
                            <th class="py-3 px-2 text-right">Quantity</th>
                            <th class="py-3 px-2 text-right">Rate</th>
                            <th class="py-3 px-2 text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @foreach ($billings as $item)
                        <tr>
                            <td class="py-2 px-2">{{ $item->description }}</td>
                            <td class="py-2 px-2 text-right">{{ $item->quantity }}</td>
                            <td class="py-2 px-2 text-right">{{ number_format($item->rate, 2) }}</td>
                            <td class="py-2 px-2 text-right">{{ number_format($item->total, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="font-semibold bg-gray-50">
                            <td colspan="3" class="py-3 px-2 text-right">Total:</td>
                            <td class="py-3 px-2 text-right">
                                {{ number_format($billings->sum('total'), 2) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>

            </div>

            <div class="mt-6 text-sm text-gray-600">

                <p class="font-semibold mt-3 text-black">Thank you for your business!</p>
            </div>
        </div>

        <div class="max-w-4xl mx-auto  p-6 sm:p-10 shadow-md mt-6">
            <h2>Prime Gorkha Services (PGCS): Terms and Conditions of Carriage</h2>
            <p><em>This is a computer-generated agreement and does not require a signature.</em></p>

            <ol>
                <li>
                    <strong>Shipper Responsibilities</strong>
                    <ul>
                        <li>Provide a valid government-issued ID.</li>
                        <li>Declare item description, quantity, and total cost in the 'List of Commodities' sheet.</li>
                        <li>Ensure accurate receiver details. PGCS is not liable for issues caused by incomplete or incorrect information.</li>
                    </ul>
                </li>
                <li>
                    <strong>Shipping Charges and Duties</strong>
                    <ul>
                        <li>Shipping charges exclude duties/taxes at the destination. The shipper agrees to pay applicable taxes for parcel clearance.</li>
                        <li>Chargeable weight is calculated as the greater of actual or volumetric weight (L×B×H ÷ 5000, in cm).</li>
                        <li>Any discrepancy in weight or size at final packaging may incur additional charges.</li>
                        <li>Weights are rounded up (e.g., 8.2 kg = 9 kg).</li>
                    </ul>
                </li>
                <li>
                    <strong>Address and Delivery Issues</strong>
                    <ul>
                        <li>Return or reshipping charges apply for incorrect addresses or parcel refusal.</li>
                        <li>Address correction fee: USD 25.</li>
                        <li>Additional charges may apply for deliveries to rural or remote areas.</li>
                    </ul>
                </li>
                <li>
                    <strong>Delivery Timelines</strong>
                    <ul>
                        <li>Economy Service: 10-20 working days.</li>
                        <li>Express Service: 4-8 working days.</li>
                        <li>Delays beyond PGCS control (e.g., customs, weather) will be communicated.</li>
                        <li>Missing parcels require 35 days for investigation and resolution.</li>
                    </ul>
                </li>
                <li>
                    <strong>Prohibited Items</strong>
                    <ul>
                        <li>No perishable, biological, contraband, or precious items are allowed.</li>
                        <li>PGCS reserves the right to inspect parcels for compliance.</li>
                    </ul>
                </li>
                <li>
                    <strong>Customs and Duties</strong>
                    <ul>
                        <li>Duties and taxes are the receiver's responsibility.</li>
                        <li>The receiver must provide all required import documentation.</li>
                        <li>PGCS may export restricted items (e.g., liquids, supplements, cosmetics, food, perishables, fragile goods) only at the full risk of the importer, who must submit proper documents.</li>
                    </ul>
                </li>
                <li>
                    <strong>Insurance and Value Declaration</strong>
                    <ul>
                        <li>PGCS accepts shipments with a declared value of up to USD 100 only.</li>
                        <li>If the shipment value exceeds USD 100, the shipper must arrange second-party (external) insurance independently.</li>
                        <li>PGCS will not be liable beyond the stated coverage.</li>
                        <li>If a parcel is not physically lost or damaged but marked missing, PGCS holds no liability beyond the insured value.</li>
                        <li>Refunds requested before international dispatch are subject to a 15% processing fee.</li>
                        <li>No refunds are provided after the parcel has exited Nepal.</li>
                    </ul>
                </li>
                <li>
                    <strong>Parcel Return Policy</strong>
                    <ul>
                        <li>PGCS does not offer return service. If a parcel is undeliverable and cannot be reshipped, it will be disposed of. No refunds or returns apply.</li>
                    </ul>
                </li>
                <li>
                    <strong>Issue Resolution Policy</strong>
                    <ul>
                        <li>Any issues during shipment (e.g., delays, customs clearance, misrouting) will be addressed only with the sender or receiver. No third-party involvement is accepted.</li>
                    </ul>
                </li>
                <li>
                    <strong>Confidentiality and Liability</strong>
                    <ul>
                        <li>PGCS keeps all customer information confidential.</li>
                        <li>PGCS is not liable for delays, loss, or non-delivery due to customs issues or incorrect recipient details.</li>
                    </ul>
                </li>
                <li>
                    <strong>Payment Terms</strong>
                    <ul>
                        <li>Shipment processing begins only after full payment is received.</li>
                    </ul>
                </li>
                <li>
                    <strong>Governing Law</strong>
                    <ul>
                        <li>This agreement is governed by Nepalese law.</li>
                    </ul>
                </li>
            </ol>

        </div>




    </div>
</body>

</html>