@extends('admin.admin_master')

@section('admin')

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

        
            <!-- Page title -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
                <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Dispatch List</h1>
            </div>

            <form action="{{ route('dispatch.airlines.bulk') }}" method="POST" class="space-y-6 bg-white dark:bg-gray-800 shadow rounded-xl p-6">
                @csrf

                <!-- Airline -->
                <div>
                    <label for="airline_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Select Airline
                    </label>
                    <select id="airline_id" name="dispatch_to" required
                        class="block w-full rounded-md border-black-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">-- Select Airline --</option>
                        <option value="qatar airways">Qatar Airways</option>
                        <option value="emirates">Emirates</option>
                        <option value="turkish airlines">Turkish Airlines</option>
                    </select>
                </div>

                <!-- Dispatch date -->
                <div>
                    <label for="dispatch_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Dispatch Date
                    </label>
                    <input type="date" id="dispatch_date" name="dispatch_date" required
                        class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100  focus:ring-indigo-500">
                </div>

                <!-- Shipments -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Select Shipments to Dispatch <span class=" border border-black-300"><input type="text" name="search" id="search"> </span>
                    </label>

                    <div class="space-y-2 h-48 overflow-y-auto border border-black-200 dark:border-gray-600 rounded-md p-4 bg-gray-50 dark:bg-gray-700">
                       

                        @foreach ($senders as $sender)
                        <label class="flex items-center space-x-3 cursor-pointer text-gray-700 dark:text-gray-200">
                            <input type="checkbox" name="shipment_ids[]" value="{{ $sender['id'] }}"
                                class="h-4 w-4 rounded border-gray-300 dark:border-gray-600 text-indigo-600 focus:ring-indigo-500">
                            <span>{{ $sender->invoiceId }} – {{ $sender->receiver->receiverName }}  – {{ $sender->receiver->receiverCountry }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <!-- Notes -->
                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Notes (Optional)
                    </label>
                    <textarea id="notes" name="notes" rows="3"
                        class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500 resize-none"></textarea>
                </div>

                <!-- Submit -->
                <div class="pt-4">
                    <button type="submit"
                        class="inline-flex items-center justify-center rounded-md bg-indigo-600 py-2 px-6 text-white font-medium hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Dispatch
                    </button>
                </div>
            </form>
       
    </div>

</div>

<!-- Add some custom styles for modern design -->
<script src="https://cdn.tailwindcss.com"></script>




@endsection