@extends('admin.admin_master')

@section('admin')

<div class="page-content">
    @if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Back Button -->
    <div className="flex  items-center mb-4">
        <a href="javascript:history.back()" class="btn btn-warning btn-rounded no-print"
            style="font-size: 15px; display: inline-flex; align-items: center; text-decoration: none;
              background-color: #FFD700; color: black; padding: 10px 10px; border-radius: 5px;
              margin-bottom: 15px; margin-top: 5px; margin-left: 20px;">
            <i class="fas fa-arrow-left mr-2"></i> Back
        </a>


    </div>

    <!-- Billing Form -->


    <form id="billingForm">
        @csrf



        <input type="hidden" name="sender_id" value="{{ $sender->id }}">
        <div class="max-w-4xl mx-auto bg-white p-6 rounded-xl shadow-md">
            <h2 class="text-2xl font-bold mb-4">Invoice / Billing</h2>

            <div id="itemsContainer">
                <div class="flex gap-4 mb-4 item-row">

                    <input type="text" name="description[]" placeholder="Description" class="w-1/3 px-4 py-2 border rounded" />
                    <input type="number" name="quantity[]" placeholder="Quantity" class="w-1/6 px-4 py-2 border rounded" />
                    <input type="number" name="rate[]" placeholder="Rate" class="w-1/6 px-4 py-2 border rounded rate-input" />
                    <button type="button" onclick="removeRow(this)" class="bg-red-500 text-white px-3 py-2 rounded hover:bg-red-600">&times;</button>
                </div>
            </div>

            <button type="button" onclick="addRow()" class="mb-4 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">+ Add Item</button>



            <button type="button" onclick="submitAndPrint()" class="bg-blue-600 text-white px-4 py-2 rounded-md">
                Print & Save Invoice
            </button>
        </div>

        <div id="invoiceDataInputs"></div>
    </form>




    <!-- Invoice Preview -->
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
                            <div>Phone: ‪+977 9708072372‬</div>
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
                            <div>Name : {{ $sender->senderName }}</div>
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
                <table class="w-full text-left  min-w-[600px]">
                    <thead>
                        <tr class="bg-gray-100 text-gray-700 uppercase text-xs">
                            <th class="py-3 px-2">Description</th>
                            <th class="py-3 px-2 text-right">Quantity</th>
                            <th class="py-3 px-2 text-right">Price</th>
                            <th class="py-3 px-2 text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody id="invoiceBody" class="text-sm">
                        <!-- Dynamic rows will be injected here -->
                    </tbody>
                    <tfoot>
                        <tr id="invoiceTotal" class=" font-semibold bg-gray-50">
                            <!-- Total row will be injected here -->
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





    <!-- TailwindCDN & Script -->
    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        function submitAndPrint() {
    const form = document.getElementById('billingForm');
    const formData = new FormData(form);

    fetch("{{ route('invoices.store') }}", {
        method: "POST",
        headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Send email with PDF
          
              
            printInvoice();  // Optional
        } else {
            alert(data.message || "Failed to save invoice.");
        }
    })
    .catch(error => {
        console.error(error);
        alert("Something went wrong while saving the invoice.");
    });
}

    </script>




    <script>
        function printInvoice() {
            const printContents = document.getElementById("printSection").innerHTML;
            const originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
            location.reload(); // Optional: reloads page to restore events
        }
    </script>
    <script>
        function addRow() {
            const row = document.querySelector('.item-row').cloneNode(true);
            row.querySelectorAll('input').forEach(input => input.value = '');
            document.getElementById('itemsContainer').appendChild(row);
            updateInvoice();
        }

        function removeRow(button) {
            const rows = document.querySelectorAll('.item-row');
            if (rows.length > 1) {
                button.closest('.item-row').remove();
                updateInvoice();
            }
        }

        function updateInvoice() {
            const rows = document.querySelectorAll('#itemsContainer .item-row');
            const invoiceBody = document.getElementById('invoiceBody');
            const invoiceTotal = document.getElementById('invoiceTotal');
            let total = 0;

            invoiceBody.innerHTML = ''; // Clear old invoice rows

            rows.forEach(row => {
                const description = row.querySelector('input[name="description[]"]').value;
                const quantity = parseFloat(row.querySelector('input[name="quantity[]"]').value) || 0;
                const rate = parseFloat(row.querySelector('input[name="rate[]"]').value) || 0;
                const rowTotal = quantity * rate;
                total += rowTotal;

                const tr = document.createElement('tr');
                tr.className = 'border-t border-gray-200 text-sm';
                tr.innerHTML = `
                <td class="py-3 px-2">${description}</td>
                <td class="py-3 px-2 text-right">${quantity}</td>
                <td class="py-3 px-2 text-right"> Rs.${rate.toFixed(2)}</td>
                <td class="py-3 px-2 text-right">Rs.${rowTotal.toFixed(2)}</td>
            `;
                invoiceBody.appendChild(tr);
            });

            invoiceTotal.innerHTML = `
            <td></td>
            <td class="py-3 px-2 text-right"></td>
            <td class="py-3 px-2 text-right">Total</td>
            <td class="py-3 px-2 text-right">Rs.${total.toFixed(2)}</td>
        `;


        }

        document.addEventListener('input', updateInvoice);
        document.addEventListener('DOMContentLoaded', updateInvoice);
    </script>

    @endsection
