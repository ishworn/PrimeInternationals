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

    .total-weight {
        padding: 2px;
        margin-top: 10px;
        color: #333;
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
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form method="post" action="{{ route('customer.store') }}" id="myForm" enctype="multipart/form-data">
                            @csrf
                            <div>
                                <div class="container mx-auto px-4 py-6">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                                        <!-- Sender Form -->
                                        <div class="bg-white shadow-md rounded-xl p-6">
                                            <h3 class="text-xl font-semibold mb-4 text-center">Sender Details</h3>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <div>
                                                    <label class="block text-sm font-medium mb-1">Sender Name</label>
                                                    <input type="text" name="senderName" id="senderName" required class="w-full border rounded px-3 py-2">
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium mb-1">Sender Phone</label>
                                                    <input type="number" name="senderPhone" id="senderPhone" class="w-full border rounded px-3 py-2">
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium mb-1">Sender Email</label>
                                                    <input type="email" name="senderEmail" id="senderEmail" class="w-full border rounded px-3 py-2">
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium mb-1">Sender Address</label>
                                                    <input type="text" name="senderAddress" id="senderAddress" class="w-full border rounded px-3 py-2">
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium mb-1">Invoice Date</label>
                                                    <input type="date" name="invoice_date" class="w-full border rounded px-3 py-2">
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Receiver Form -->
                                        <div class="bg-white shadow-md rounded-xl p-6">
                                            <h3 class="text-xl font-semibold mb-4 text-center">Receiver Details</h3>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <div>
                                                    <label class="block text-sm font-medium mb-1">Receiver Name</label>
                                                    <input type="text" name="receiverName" required class="w-full border rounded px-3 py-2">
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium mb-1">Receiver Phone</label>
                                                    <input type="text" name="receiverPhone" class="w-full border rounded px-3 py-2">
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium mb-1">Receiver Email</label>
                                                    <input type="email" name="receiverEmail" class="w-full border rounded px-3 py-2">
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium mb-1">Postal Code</label>
                                                    <input type="text" name="receiverPostalcode" class="w-full border rounded px-3 py-2">
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium mb-1">Country</label>
                                                    <input type="text" name="receiverCountry" class="w-full border rounded px-3 py-2">
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium mb-1">Address</label>
                                                    <textarea name="receiverAddress" rows="2" class="w-full border rounded px-3 py-2"></textarea>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <!-- Box Section -->
                                <div id="boxSection" class="form-section">
                                    <div class="container-fluid">

                                        <!-- Box Container Section -->
                                        <div id="boxContainer" class="mt-4"></div>
                                        <!-- Add Box Button -->
                                        <div class="text-center">
                                            <button type="button" class="btn btn-primary  m-3" onclick="addBox()">Add Box</button>
                                            <div class="form-group    navbar-light bg-light  mx-9  ">
                                                <input type="submit" class="btn btn-info" value="Submit All Data">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.tailwindcss.com"></script>
<script>
    $(document).ready(function() {
        // Initialize DataTable for the sender table




        // Initialize DataTable for the receiver table
        $('#receiverTable').DataTable();
    });

    function submitForm() {
        const formData = new FormData(document.getElementById('myForm'));
        // Create a simplified 'boxes' array to store only box numbers and sender_id
        const simplifiedBoxes = boxes.map((boxId, index) => {
            return {
                box_number: ` ${index + 1}`, // You can customize this format
            };
        });
        // Append the simplified box data to the FormData object
        formData.append('boxes', JSON.stringify(simplifiedBoxes));
        // Submit the form with the simplified box data
        fetch('/submit', {
                method: 'POST',
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                console.log('Form submitted successfully:', data);
            })
            .catch(error => {
                console.error('Error submitting form:', error);
            });
    }


    // Array to store box IDs
    let boxes = [];
    // Function to add a new box///
    function addBox() {
        const boxId = Date.now().toString(); // Unique ID for each box
        boxes.push(boxId);
        const boxDiv = document.createElement('div');
        boxDiv.className = 'box-section';
        boxDiv.dataset.boxId = boxId;
        boxDiv.innerHTML = `
        <div class="box-header">
            <h4><span class="box-number">Box ${boxes.length}</span></h4>
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
         <div style="display: flex; align-items: center;">
    <button type="button" class="add-row-button" onclick="addRow('${boxId}')">Add Item</button>

</div>

    `;
        document.getElementById('boxContainer').appendChild(boxDiv);
        addRow(boxId); // Add the first row to the new box
        updateBoxNumbers();
    }

    function deleteBox(boxId) {
        boxes = boxes.filter(id => id !== boxId);
        document.querySelector(`[data-box-id="${boxId}"]`).remove();
        updateBoxNumbers();
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
        <td><input type="text" name="boxes[${boxId}][items][${rowIndex}][quantity]" required class="form-control"></td>
        <td><input type="number" name="boxes[${boxId}][items][${rowIndex}][unit_rate]" step="0.001" class="form-control"></td>
        <td><input type="decimal" name="boxes[${boxId}][items][${rowIndex}][amount]" readonly class="form-control"></td>
        <td><button type="button" class="delete-row-button btn btn-danger" onclick="deleteRow(this)">×</button></td>
           
    `;
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

    function deleteRow(button) {
        const row = button.closest('tr');
        const tbody = row.parentElement;
        row.remove();
        updateRowNumbers(tbody.closest('.box-section'));
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

    function toggleMinimize(button) {
        const content = button.closest('.box-header').nextElementSibling;
        content.style.display = content.style.display === 'none' ? 'block' : 'none';
        button.textContent = content.style.display === 'none' ? '+' : '-';
    }
</script>
<script>
    document.getElementById('senderName').addEventListener('blur', function() {
        const name = this.value;

        if (name) {
            fetch(`/check-sender?name=${name}`)
                .then(response => response.json())
                .then(data => {
                    if (data.exists) {
                        document.getElementById('senderPhone').value = data.phone;
                        document.getElementById('senderEmail').value = data.email;
                        // Fill other fields similarly
                    } else {
                        console.log('Sender not found');
                    }
                })
                .catch(error => console.error('Error:', error));
        }
    });
</script>

@endsection