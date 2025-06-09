<!DOCTYPE html>
<html>
<head>
    <title>Shipment PDF</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; }
        h1 { text-align: center; margin-bottom: 20px; }
        table {
            width: 100%; border-collapse: collapse; margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #000; padding: 8px; text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
            <h2 class="mb-4">Shipment Details - ID: {{ $shipment->id }}</h2>

            <!-- Shipment Info -->
            <div class="card mb-4">
                <div class="card-header">Shipment Info</div>
                <div class="card-body">
                    <p><strong>Shipment Number:</strong> {{ $shipment->shipment_number }}</p>
                    <p><strong>Sender IDs:</strong> {{ implode(', ', $shipment->sender_id) }}</p>
                    <p><strong>Total Weight:</strong> {{ $totalWeight }} kg</p>
                    <p><strong>Total Boxes:</strong> {{ $totalBoxes }}</p>
                    
              
                    <p><strong>Created At:</strong> {{ $shipment->created_at }}</p>
                 
                    <!-- Add other shipment fields as needed -->
                </div>
            </div>

           
           


        </div>
</body>
</html>
