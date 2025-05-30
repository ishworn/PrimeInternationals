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
      <!-- <div class="print-hidden mb-4 flex gap-4 justify-end">
                 <button onclick="window.print()" class="flex items-center gap-2 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    Print
                </button> 
                <a href="{{ route('export.excel', $sender->id) }}" class="flex items-center gap-2 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    Export to Excel
                </a>
                 <a href="{{ route('invoice.print', $sender->id) }}" class="flex items-center gap-2 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    Print Invoice
                </a> 
            </div> -->
      <!-- Invoice Content -->
      <div class="bg-gray-300 p-8 sm:p-10 rounded-lg max-w-5xl mx-auto shadow-lg text-gray-800">

        <!-- Header -->
        <div class="relative mb-8 border-b pb-2">
          <div class="text-center">
            <h1 class="text-2xl font-bold text-blue-700 uppercase tracking-widest">Prime Gurkha Logistics</h1>
            <p class="text-sm text-gray-600">Aloknagor-310, Kathmandu</p>
            <p class="text-sm text-gray-600">Phone: +977 9708072372</p>
            <p class="text-sm text-gray-600">Email: primegurkha@gmail.com</p>
          </div>
          <div class="absolute bottom-0 right-0 text-sm text-left">
            <p><span class="font-medium">Invoice No:</span> {{ $sender->invoiceId ?? 'INV-001' }}</p>
            <p><span class="font-medium">Date:</span> {{ $sender->created_at }}</p>
          </div>
        </div>

        <!-- Shipper and Consignee -->
        <div class="grid md:grid-cols-2 gap-6 mb-8">
          <div class="bg-white p-4 rounded shadow-md">
            <h2 class="text-lg font-semibold mb-2 text-blue-600">Shipper</h2>
            <p><strong>Name:</strong> {{ $sender->senderName }}</p>
            <p><strong>Email:</strong> {{ $sender->senderEmail }}</p>
            <p><strong>Phone:</strong> {{ $sender->senderPhone }}</p>
            <p><strong>Address:</strong> {{ $sender->senderAddress }}</p>
          </div>
          <div class="bg-white p-4 rounded shadow-md">
            <h2 class="text-lg font-semibold mb-2 text-blue-600">Consignee</h2>
            @foreach($receivers as $receiver)
            <p><strong>Name:</strong> {{ $receiver->receiverName }}</p>
            <p><strong>Email:</strong> {{ $receiver->receiverEmail }}</p>
            <p><strong>Phone:</strong> {{ $receiver->receiverPhone }}</p>
            <p><strong>Address:</strong> {{ $receiver->receiverAddress }}</p>
            <p><strong>Postal Code:</strong> {{ $receiver->receiverPostalcode }}</p>
            @endforeach
          </div>
        </div>

        <!-- Shipment Details -->
        <div class="grid md:grid-cols-2 gap-6 mb-8">
          <div class="bg-white p-4 rounded shadow-md">
            <h2 class="text-lg font-semibold mb-2 text-blue-600">Shipment Info</h2>
            @foreach($shipments as $shipment)
            <p><strong>Via:</strong> {{ $shipment->shipment_via }}</p>
            <p><strong>Weight:</strong> {{ $shipment->actual_weight }} kg</p>
            <p><strong>Dimension:</strong> {{ $shipment->dimension }}</p>
            @endforeach
            <p><strong>Destination:</strong> {{ $receivers[0]->receiverCountry ?? '' }}</p>
            <p><strong>Total Boxes:</strong> {{ $totalBoxes ?? '0' }}</p>
          </div>

          <div class="bg-white p-4 rounded shadow-md">
            <h2 class="text-lg font-semibold mb-2 text-blue-600">Dispatch & Payment</h2>
            @if($dispatchs->isEmpty())
            <p>Despatch To: <span class="text-gray-400">N/A</span></p>
            <p>Despatch Time: <span class="text-gray-400">N/A</span></p>
            @else
            @foreach($dispatchs as $dispatch)
            <p><strong>Despatch To:</strong> {{ $dispatch->dispatch_by }}</p>
            <p><strong>Time:</strong> {{ $dispatch->dispatched_at }}</p>
            @endforeach
            @endif

            @if($payments->isEmpty())
            <p><strong>Amount:</strong> N/A</p>
            <p><strong>Method:</strong> N/A</p>
            @else
            @foreach($payments as $payment)
            <p><strong>Amount:</strong> ${{ $payment->total_paid }}</p>
            <p><strong>Method:</strong> {{ $payment->payment_method }}</p>
            @endforeach
            @endif
          </div>
        </div>

        <!-- Items Table -->
        <div class="bg-white p-4 rounded shadow-md mb-8 overflow-x-auto">
          <h2 class="text-lg font-semibold text-blue-600 mb-3">Box Contents</h2>
          <table class="min-w-full text-sm text-left border">
            <thead class="bg-gray-100 uppercase font-semibold text-gray-700">
              <tr>
                <th class="px-3 py-2 border">Box</th>
                <th class="px-3 py-2 border">#</th>
                <th class="px-3 py-2 border">Item</th>
                <th class="px-3 py-2 border">HS Code</th>
                <th class="px-3 py-2 border">Qty</th>
                <th class="px-3 py-2 border text-right">Unit Rate ($)</th>
                <th class="px-3 py-2 border text-right">Amount ($)</th>
              </tr>
            </thead>
            <tbody>
              @foreach($sender->boxes as $box)
              <tr class="bg-blue-50 font-semibold">
                <td class="px-3 py-2 border" colspan="7">Box: {{ $box->box_number }}</td>
              </tr>
              @foreach($box->items as $index => $item)
              <tr>
                <td class="px-3 py-2 border"></td>
                <td class="px-3 py-2 border">{{ $index + 1 }}</td>
                <td class="px-3 py-2 border">{{ $item->item }}</td>
                <td class="px-3 py-2 border">{{ $item->hs_code }}</td>
                <td class="px-3 py-2 border">{{ $item->quantity }}</td>
                <td class="px-3 py-2 border text-right">{{ number_format($item->unit_rate, 2) }}</td>
                <td class="px-3 py-2 border text-right">{{ number_format($item->amount, 2) }}</td>
              </tr>
              @endforeach
              @endforeach
              <tr class="bg-gray-100 font-semibold">
                <td colspan="4" class="px-3 py-2 border text-right">Total Qty</td>
                <td class="px-3 py-2 border">{{ $totalQuantity ?? '18' }}</td>
                <td class="px-3 py-2 border text-right">Grand Total</td>
                <td class="px-3 py-2 border text-right">${{ $grandTotal ?? 'N/A' }}</td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Billing Table -->
        <div class="bg-white p-4 rounded shadow-md mb-6">
          <h2 class="text-lg font-semibold text-blue-600 mb-3">Billing Summary</h2>
          <table class="min-w-full text-sm text-left border">
            <thead class="bg-gray-100 font-semibold text-gray-700 uppercase">
              <tr>
                <th class="px-3 py-2 border">Description</th>
                <th class="px-3 py-2 border text-right">Qty</th>
                <th class="px-3 py-2 border text-right">Rate</th>
                <th class="px-3 py-2 border text-right">Total</th>
              </tr>
            </thead>
            <tbody>
              @if ($billings->isEmpty())
              <tr>
                <td colspan="4" class="text-center py-4 text-gray-500">No bill has been generated.</td>
              </tr>
              @else
              @foreach ($billings as $item)
              <tr>
                <td class="px-3 py-2 border">{{ $item->description }}</td>
                <td class="px-3 py-2 border text-right">{{ $item->quantity }}</td>
                <td class="px-3 py-2 border text-right">{{ number_format($item->rate, 2) }}</td>
                <td class="px-3 py-2 border text-right">{{ number_format($item->total, 2) }}</td>
              </tr>
              @endforeach
              @endif
            </tbody>
            @if (!$billings->isEmpty())
            <tfoot>
              <tr class="bg-gray-100 font-semibold">
                <td colspan="3" class="px-3 py-2 border text-right">Total:</td>
                <td class="px-3 py-2 border text-right">${{ number_format($billings->sum('total'), 2) }}</td>
              </tr>
            </tfoot>
            @endif
          </table>
        </div>

        <!-- Footer -->
        <div class="text-sm text-gray-700 border-t pt-4 mt-6 flex justify-between items-end">
          <div>
            <p class="font-semibold text-blue-600">NOTES:</p>
            <p>We declare that the above-mentioned goods are made in Nepal and descriptions are true.</p>
          </div>
          <div class="text-right">
            <p class="font-semibold">Signature & Stamp</p>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

@endsection