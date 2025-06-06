@extends('admin.admin_master')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>


<style>
    .form-container {
        max-width: 100%;
        margin: auto;
        padding: 20px;
        background-color: #f9f9f9;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }

    .form-group input,
    .form-group textarea,
    .form-group select {
        width: 100%;
        padding: 8px;
        box-sizing: border-box;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    .form-group input[type="submit"] {
        background-color: #4CAF50;
        color: white;
        border: none;
        cursor: pointer;
        padding: 10px 20px;
        border-radius: 4px;
    }

    .form-group input[type="submit"]:hover {
        background-color: #45a049;
    }

    .box-section {
        margin-top: 20px;
        border: 1px solid #ddd;
        padding: 15px;
        border-radius: 5px;
        background-color: #fff;
    }

    .box-section h3 {
        margin-bottom: 10px;
        color: #333;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }

    table,
    th,
    td {
        border: 1px solid #ddd;
    }

    th,
    td {
        padding: 8px;
        text-align: left;
    }

    th {
        background-color: #f2f2f2;
    }

    .add-row-button,
    .add-box-button {
        margin-top: 10px;
        background-color: #008CBA;
        color: white;
        border: none;
        padding: 8px 16px;
        cursor: pointer;
        border-radius: 4px;
    }

    .add-row-button:hover,
    .add-box-button:hover {
        background-color: #007B9E;
    }

    .sender-receiver-container {
        display: flex;
        gap: 20px;
        margin-bottom: 20px;
    }

    .sender-receiver-container>div {
        flex: 1;
        border: 1px solid #ddd;
        padding: 15px;
        border-radius: 5px;
        background-color: #fff;
    }

    .top-section {
        display: flex;
        gap: 20px;
        margin-bottom: 20px;
    }

    .top-section>div {
        flex: 1;
    }

    .box-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }

    .box-header button {
        margin-left: 10px;
    }

    .minimize-button,
    .delete-box-button,
    .delete-row-button {
        background-color: #ff4d4d;
        color: white;
        border: none;
        padding: 5px 10px;
        cursor: pointer;
        border-radius: 4px;
    }

    .minimize-button:hover,
    .delete-box-button:hover,
    .delete-row-button:hover {
        background-color: #cc0000;
    }

    @media (max-width: 768px) {

        th,
        td {
            padding: 8px;
            font-size: 14px;
        }

        .box-content {
            padding: 10px;
        }

        .add-row-button {
            font-size: 14px;
            padding: 8px 16px;
        }
    }
</style>
<div class="page-content">
    <a href="javascript:history.back()" class="btn btn-warning"
        style="font-size: 15px; display: inline-flex; align-items: center; text-decoration: none; 
          background-color: #FFD700; color: black; padding: 10px 10px; border-radius: 5px; 
          margin-bottom: 10px; margin-top: 10px; margin-left: 10px;">
        <i class="fas fa-arrow-left" style="margin-right: 5px;"></i> Back
    </a>
    <div class="container-fluid">

        <div class="row">
            <h2 class="card-title">Edit Customer Page </h2>
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form method="post" action="{{ route('customer.update') }}" id="myForm" enctype="multipart/form-data">
                            @csrf

                            <div>
                                <div class="container mx-auto px-4 py-6">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                                        <!-- Sender Form -->
                                        <div class="bg-white shadow-md rounded-xl p-6">
                                            <h3 class="text-xl font-semibold mb-4 text-center">Sender Details</h3>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <div>
                                                    <label class="block text-sm font-medium mb-1">Sender ID</label>
                                                    <input type="text" id='id' value="{{ $sender->id }}" name="id" readonly required class="w-full border rounded px-3 py-2">
                                                </div>

                                                <div>
                                                    <label class="block text-sm font-medium mb-1">Sender Name</label>
                                                    <input type="text" id='senderName' value="{{ $sender->senderName }}" class="w-full border rounded px-3 py-2" name="senderName" required>


                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium mb-1">Sender Phone</label>
                                                    <input type="text" id='senderPhone' value="{{ $sender->senderPhone }}" name="senderPhone" class="w-full border rounded px-3 py-2">
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium mb-1">Sender Email</label>
                                                    <input type="email" id='senderEmail' value="{{ $sender->senderEmail }}" name="senderEmail" class="w-full border rounded px-3 py-2">
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium mb-1">Sender Address</label>
                                                    <input type="text" id='senderAddress' value="{{ $sender->senderAddress }}" name="senderAddress" class="w-full border rounded px-3 py-2">
                                                </div>
                                                <div>
                                                    @foreach($shipments as $shipment)
                                                    <label class="block text-sm font-medium mb-1">Invoice Date</label>
                                                    <input type="date" name="invoice_date" value="{{ $shipment  ->invoice_date }}" class="form-control" required class="w-full border rounded px-3 py-2">
                                                    @endforeach
                                                </div>

                                            </div>
                                        </div>
                                        @foreach($receivers as $receiver)
                                        <!-- Receiver Form -->
                                        <div class="bg-white shadow-md rounded-xl p-6">
                                            <h3 class="text-xl font-semibold mb-4 text-center">Receiver Details</h3>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <div>
                                                    <label class="block text-sm font-medium mb-1">Receiver Name</label>
                                                    <input type="text" name="receiverName" value="{{ $receiver->receiverName }}" required class="w-full border rounded px-3 py-2">
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium mb-1">Receiver Phone</label>
                                                    <input type="text" name="receiverPhone" value="{{ $receiver->receiverPhone }}" class="w-full border rounded px-3 py-2">
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium mb-1">Receiver Email</label>
                                                    <input type="email" name="receiverEmail" value="{{ $receiver->receiverEmail }}" class="w-full border rounded px-3 py-2">
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium mb-1">Postal Code</label>
                                                    <input type="text" name="receiverPostalcode" value="{{ $receiver->receiverPostalcode }}" class="w-full border rounded px-3 py-2">
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium mb-1">Country</label>
                                                    <input type="text" name="receiverCountry" value="{{ $receiver->receiverCountry }}" required class="w-full border rounded px-3 py-2">
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium mb-1">Address</label>
                                                    <textarea name="receiverAddress" value="{{ $receiver->receiverAddress }}" class="w-full border rounded px-3 py-2"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach

                                    </div>
                                </div>
                            </div>







                            <!-- Box Section -->

                            <div id="boxSection" class="form-section">

                                <div id="boxContainer">
                                    @foreach($sender->boxes as $box)
                                    <div class="box-section" data-box-id="{{ $box->id }}">
                                        <div class="box-header">
                                            <h4><span class="box-number">Box {{ $loop->iteration }}</span></h4>
                                            <button type="button" class="minimize-button" onclick="toggleMinimize(this)">-</button>
                                            <button type="button" class="delete-box-button" onclick="deleteBox('{{ $box->id }}')">×</button>
                                        </div>
                                        <div class="box-content">
                                            <div class="table-wrapper">
                                                <table>
                                                    <thead>
                                                        <tr>
                                                            <th>Id</th>
                                                            <th>Item</th>
                                                            <th>HS Code</th>
                                                            <th>Quantity</th>
                                                            <th>Unit Rate</th>
                                                            <th>Amount</th>
                                                            <th>Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($box->items as $item)
                                                        <tr data-row-index="{{ $loop->index }}">
                                                            <td>{{ $loop->iteration }}</td>
                                                            <td><input type="text" name="boxes[{{ $box->id }}][items][{{ $loop->index }}][item]" value="{{ $item->item }}" required class="form-control"></td>
                                                            <td><input type="text" name="boxes[{{ $box->id }}][items][{{ $loop->index }}][hs_code]" value="{{ $item->hs_code }}" class="form-control"></td>
                                                            <td><input type="text" name="boxes[{{ $box->id }}][items][{{ $loop->index }}][quantity]" value="{{ $item->quantity }}" required class="form-control"></td>
                                                            <td><input type="number" name="boxes[{{ $box->id }}][items][{{ $loop->index }}][unit_rate]" value="{{ $item->unit_rate }}" class="form-control"></td>
                                                            <td><input type="number" name="boxes[{{ $box->id }}][items][{{ $loop->index }}][amount]" value="{{ $item->amount }}" readonly class="form-control"></td>
                                                            <td><button type="button" class="delete-row-button btn btn-danger" onclick="deleteRow(this)">×</button></td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                            <button type="button" class="add-row-button" onclick="addRow('{{ $box->id }}')">Add Item</button>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                <div class="text-center">
                                    <button type="button" class="btn btn-primary  m-3" onclick="addBox()">Add Box</button>
                                </div>

                            </div>




                            <div class="form-group    navbar-light bg-light  mx-9  ">
                                <input type="submit" class="btn btn-info waves-effect waves-light" value="Update Customer">

                            </div>
                            <div id="next-box-number" data-next-box-number="{{ $nextBoxNumber }}"></div>

                        </form>

                    </div>
                </div>
            </div> <!-- end col -->
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.tailwindcss.com"></script>

<script>
    document.querySelectorAll('.nav-link').forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            document.querySelectorAll('.form-section').forEach(section => {
                section.style.display = 'none';
            });
            document.getElementById(e.target.dataset.target).style.display = 'block';
            link.parentElement.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active'));
            link.classList.add('active');
        });
    });


    let nextBoxNumber = document.getElementById('next-box-number').getAttribute('data-next-box-number');
    nextBoxNumber = Number(nextBoxNumber)
    // Check the value in the console


    let boxes = [];

    // boxes.push(boxes.length);
    function addBox() {
        const boxId = Date.now().toString(); // Unique ID for each box
        boxes.push(boxId);
        const boxDiv = document.createElement('div');
        boxDiv.className = 'box-section';
        boxDiv.dataset.boxId = boxId;
        boxDiv.innerHTML = `
        <div class="box-header">
            <h4><span class="box-number">Box ${nextBoxNumber+boxes.length}</span></h4>
            <div>
                <button type="button" class="minimize-button" onclick="toggleMinimize(this)">-</button>
                <button type="button" class="delete-box-button" onclick="deleteBox('${boxId}')">×</button>
            </div>
        </div>
        <div class="box-content">
         <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Item</th>
                        <th>HS Code</th>
                        <th>Quantity</th>
                        <th>Unit Rate</th>
                        <th>Amount</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
            </div>
            <button type="button" class="add-row-button" onclick="addRow('${boxId}')">Add Item</button>
        </div>`;
        document.getElementById('boxContainer').appendChild(boxDiv);
        addRow(boxId); // Add the first row to the new box
        updateBoxNumbers();
    }

    function deleteBox(boxId) {
        boxes = boxes.filter(id => id !== boxId);
        document.querySelector(`[data-box-id="${boxId}"]`).remove();
        updateBoxNumbers();
    }

    function deleteRow(button) {
        const row = button.closest('tr');
        const tbody = row.parentElement;
        row.remove();
        updateRowNumbers(tbody.closest('.box-section'));
    }

    function updateBoxNumbers() {
        document.querySelectorAll('.box-section').forEach((box, index) => {
            const boxNumberSpan = box.querySelector('.box-number');
            if (boxNumberSpan) {
                boxNumberSpan.textContent = `Box ${index + 1}`;
            }
            // Update all input names in this box
            const boxIndex = index;
            box.querySelectorAll('input').forEach(input => {
                const name = input.name;
                if (name.includes('boxes[OLD_INDEX]')) {
                    input.name = name.replace('boxes[OLD_INDEX]', `boxes[${boxIndex}]`);
                }
            });
        });
    }

    function addRow(boxId) {
        const box = document.querySelector(`[data-box-id="${boxId}"]`);
        const tbody = box.querySelector('tbody');
        const rows = tbody.querySelectorAll('tr');
        const rowIndex = rows.length;
        const row = document.createElement('tr');
        row.dataset.rowIndex = rowIndex;
        row.innerHTML = `
         <td class="d-none d-md-table-cell">${rowIndex + 1}</td> <!-- Hide on mobile -->
        <td><input type="text" name="boxes[${boxId}][items][${rowIndex}][item]" required class="form-control"></td>
        <td><input type="text" name="boxes[${boxId}][items][${rowIndex}][hs_code]" class="form-control"></td>
        <td><input type="text" name="boxes[${boxId}][items][${rowIndex}][quantity]"  class="form-control"></td>
        <td><input type="number" name="boxes[${boxId}][items][${rowIndex}][unit_rate]" step="0.001"  class="form-control"></td>
        <td><input type="number" name="boxes[${boxId}][items][${rowIndex}][amount]" readonly class="form-control"></td>
        <td><button type="button" class="delete-row-button btn btn-danger" onclick="deleteRow(this)">×</button></td> `;
        // Add calculation logic
        const quantityInput = row.querySelector('input[name*="quantity"]');
        const unitRateInput = row.querySelector('input[name*="unit_rate"]');
        const amountInput = row.querySelector('input[name*="amount"]');
        const calculateAmount = () => {
            const quantity = parseFloat(quantityInput.value) || 0;
            const unitRate = parseFloat(unitRateInput.value) || 0;
            amountInput.value = (quantity * unitRate).toFixed(2);
        };
        quantityInput.addEventListener('input', calculateAmount);
        unitRateInput.addEventListener('input', calculateAmount);
        tbody.appendChild(row);
        updateRowNumbers(box);



    }


    function toggleMinimize(button) {
        const content = button.closest('.box-header').nextElementSibling;
        content.style.display = content.style.display === 'none' ? 'block' : 'none';
        button.textContent = content.style.display === 'none' ? '+' : '-';
    }

    function updateRowNumbers(box) {
        const tbody = box.querySelector('tbody');
        tbody.querySelectorAll('tr').forEach((row, index) => {
            row.querySelector('td:first-child').textContent = index + 1;
            // Update all input names in this row
            const boxIndex = Array.from(box.parentElement.children).indexOf(box);
            row.querySelectorAll('input').forEach(input => {
                const name = input.name;
                input.name = name
                    .replace(/boxes\[\d+\]/g, `boxes[${boxIndex}]`)
                    .replace(/items\[\d+\]/g, `items[${index}]`);
            });
        });
    }
</script>

@endsection