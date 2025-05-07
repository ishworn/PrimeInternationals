@extends('admin.admin_master')

@section('admin')

<div class="page-content">
    @if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif
    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif
    <a href="javascript:history.back()" class="btn btn-warning btn-rounded no-print"
        style="font-size: 15px; display: inline-flex; align-items: center; text-decoration: none; 
          background-color: #FFD700; color: black; padding: 10px 10px; border-radius: 5px; 
          margin-bottom: 15px; margin-top: 5px; margin-left: 20px;">
        <i class="fas fa-arrow-left" style="margin-right: 5px;"></i> Back
    </a>
    <div class="container-fluid">
        <!-- Start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0" style="font-size: 24px; font-weight: bold;">Manage Expenses</h4>
                </div>
            </div>
        </div>
        <!-- End page title -->
        <!-- Trackings Table -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-lg">
                    <div class="card-body">
                        <section class="w-full max-w-3xl bg-white p-6 rounded-lg shadow-md">
                            <form action="{{ route('expenses.store') }}" method="POST" class="space-y-4" enctype="multipart/form-data">
                                @csrf

                                <div class="grid grid-cols-3 gap-4">
                                    <!-- Expense Name -->
                                    <div>
                                        <label class="block text-gray-700 font-medium">Expense Name</label>
                                        <input type="text" name="expense_name" class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500" placeholder="Enter expense name" required>
                                    </div>

                                    <!-- Amount -->
                                    <div>
                                        <label class="block text-gray-700 font-medium">Amount (NPR)</label>
                                        <input type="number" name="amount" class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500" placeholder="Enter amount" required>
                                    </div>

                                    <!-- Date -->
                                    <div>
                                        <label class="block text-gray-700 font-medium">Date</label>
                                        <input type="date" name="date" class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500" required>
                                    </div>

                                    <!-- Category -->
                                    <div>
                                        <label class="block text-gray-700 font-medium">Category</label>
                                        <select name="category" id="category" class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500" onchange="toggleOtherCategory()">
                                            <option value="Food">Food</option>
                                            <option value="Transport">Transport</option>
                                            <option value="Bills">Bills</option>
                                            <option value="Shopping">Shopping</option>
                                            <option value="Others">Others</option>
                                        </select>
                                    </div>

                                    <!-- Other Category (Hidden by Default) -->
                                    <div id="otherCategoryDiv" class="hidden">
                                        <label class="block text-gray-700 font-medium">Specify Other</label>
                                        <input type="text" name="other_category" class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500" placeholder="Enter category">
                                    </div>

                                    <!-- Payment Method -->
                                    <div>
                                        <label class="block text-gray-700 font-medium">Payment Method</label>
                                        <div class="flex items-center gap-4 mt-2">
                                            <label class="flex items-center">
                                                <input type="radio" name="payment_method" value="Cash" class="mr-2" required>
                                                <span>Cash</span>
                                            </label>
                                            <label class="flex items-center">
                                                <input type="radio" name="payment_method" value="Bank" class="mr-2" required>
                                                <span>Bank</span>
                                            </label>
                                        </div>
                                    </div>

                                    <!-- Image Upload -->
                                    <div>
                                        <label class="block text-gray-700 font-medium">Upload Receipt</label>
                                        <input type="file" name="receipt" class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500">
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700 transition duration-300">
                                    Add Expense
                                </button>
                            </form>
                        </section>
                    </div>
                </div>
            </div>
        </div> <!-- End col -->




        <div class="row">
            <div class="col-12">
                <div class="card shadow-lg">
                    <div class="card-body">
                        <!-- Datatable -->
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

                            </div>


                            <table id="datatable" class="table table-striped table-bordered dt-responsive nowrap" style="width: 100%; margin-top: 50px;">
                                <thead class="bg-primary text-white">
                                    <tr>
                                        <th>Sl</th>
                                        <th>Expenses Name</th>

                                        <th>Category</th>
                                        <th>Payment Method</th>
                                        <th>Date</th>
                                        <th> Amount</th>
                                        <th>Receipt</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($expenses as $key => $expense)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $expense-> expense_name }}</td>

                                        <td>
                                            @if ($expense->category == 'Others')
                                            {{ $expense->other_category ?: 'N/A' }}
                                            @else
                                            {{ $expense->category ?: 'N/A' }}
                                            @endif
                                        </td>


                                        <td>{{ $expense-> payment_method ?? 'N/A' }} </td>
                                        <td>{{ $expense-> date ?? 'N/A' }}</td>
                                        <td>{{ $expense-> amount ?? 'N/A' }}</td>
                                        <td>
                                            @if ($expense->receipt)
                                            <img src="{{ asset('storage/' . $expense->receipt) }}" alt="Receipt" width="100" height="100">
                                            @else
                                            N/A
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-light">
                                    <tr>
                                        <td colspan="5"><strong>Total Amount: </strong></td>
                                        <td id="totalAmount"></td> <!-- Initial value is set to 0.00 -->
                                    </tr>
                                </tfoot>
                            </table>
                        </div> <!-- End Table -->
                    </div>
                </div>
            </div>
        </div>





    </div> <!-- End row -->
</div> <!-- container-fluid -->
</div>

<!-- Add some custom styles for modern design -->
<script src="https://cdn.tailwindcss.com"></script>
<script>
    function toggleOtherCategory() {
        const category = document.getElementById('category').value;
        const otherCategoryDiv = document.getElementById('otherCategoryDiv');
        if (category === 'Others') {
            otherCategoryDiv.classList.remove('hidden');
        } else {
            otherCategoryDiv.classList.add('hidden');
        }
    }
</script>
<style>
    .btn {
        transition: all 0.3s ease;
    }

    .btn:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
    }

    .card {
        border-radius: 8px;
        overflow: hidden;
    }

    table th,
    table td {
        text-align: center;
        padding: 12px;
        font-size: 16px;
    }

    table tbody tr:hover {
        background-color: #f1f1f1;
    }

    table th {
        background-color: #3e8e41;
        color: white;
        text-align: center;
    }

    table td {
        vertical-align: middle;
    }

    .bg-light {
        background-color: #f8f9fa;
    }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
        // Initial total calculation (on page load)
        calculateTotal();

        // Event listener for date filters
        $('#fromDate, #toDate').on('change', function() {
            filterTable();
        });

        // Function to filter the table and calculate the total amount
        function filterTable() {
            var fromDate = $('#fromDate').val();
            var toDate = $('#toDate').val();
            var total = 0;

            // Ensure that the dates are valid before filtering
            if (fromDate && toDate) {
                $('#datatable tbody tr').each(function() {
                    var rowDate = $(this).find('td:eq(4)').text(); // The 5th column (index 4) is the date column
                    var rowAmount = $(this).find('td:eq(5)').text().replace(/,/g, ''); // Remove commas if any
                    if (rowDate) {
                        var rowDateValue = new Date(rowDate);
                        var fromDateValue = new Date(fromDate);
                        var toDateValue = new Date(toDate);

                        // Check if the row date is within the selected range
                        if (rowDateValue >= fromDateValue && rowDateValue <= toDateValue) {
                            $(this).show();
                            // Add the amount to the total if the row matches the date filter
                            total += parseFloat(rowAmount); // Convert to number and add to total
                        } else {
                            $(this).hide();
                        }
                    }
                });
            } else {
                // No filter applied, show all rows and calculate the total for all data
                $('#datatable tbody tr').each(function() {
                    $(this).show();
                    var rowAmount = $(this).find('td:eq(5)').text().replace(/,/g, ''); // Get the amount column and remove commas
                    total += parseFloat(rowAmount); // Add the amount to the total
                });
            }

            // Update the total amount display in the footer
            $('#totalAmount').text(total.toFixed(2)); // Display the total with two decimal places
        }

        // Function to calculate the total amount (for all rows initially)
        function calculateTotal() {
            var total = 0;

            $('#datatable tbody tr').each(function() {
                var rowAmount = $(this).find('td:eq(5)').text().replace(/,/g, ''); // Get the amount column and remove commas
                total += parseFloat(rowAmount); // Add the amount to the total
            });

            // Update the total amount display in the footer
            $('#totalAmount').text(total.toFixed(2)); // Display the total with two decimal places
        }
    });
</script>


@endsection