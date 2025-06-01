<?php


namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;


use App\Models\Sender;
use App\Models\Dispatch;
use Illuminate\Http\Request;
use App\Models\Shipments;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;



class DispatchManagementController extends Controller
{



    public function index()
    {
        $senders = Sender::with('receiver', 'dispatch',)
            ->doesntHave('dispatch') // Filter senders who do not have any payments
            ->get();
        // Fetch all tracking records
        return view('backend.dispatch.index', compact('senders')); // Return the index view with tracking data
    }



    public function store(Request $request)
    {
        Dispatch::create([

            'sender_id' => $request->sender_id,
            'dispatch_by'  => $request->dispatch_by,
            'dispatched_at' => $request->dispatched_at,
            'status' => 'dispatch'
        ]);

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Payment saved successfully!');
    }



    public function airline()
    {
        // Fetch all airlines
        $senders = Sender::with('receiver', 'dispatch',)
            ->doesntHave('dispatch') // Filter senders who do not have any payments
            ->get();

        return view('backend.dispatch.airlines', compact('senders'));
    }

    public function agencies()
    {
        // Fetch all agencies
        $senders = Sender::with('receiver', 'dispatch',)
            ->doesntHave('dispatch') // Filter senders who do not have any payments
            ->get();

        return view('backend.dispatch.agencies', compact('senders'));
    }








    public function agenciesBulkDispatch(Request $request)
    {
        $lastShipment = Shipments::orderByDesc('id')->first();
        if ($lastShipment && Str::startsWith($lastShipment->shipment_number, 'PG')) {
            $lastNumber = (int) Str::replaceFirst('PG', '', $lastShipment->shipment_number);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 101; // starting point
        }
        $newShipmentNumber = 'PG' . $newNumber;
        // Validate request
        $validated = $request->validate([
            'dispatch_to' => 'required|string|max:255',
            'dispatch_date' => 'required|date',
            'selected_senders' => 'required|string', // Comma-separated sender IDs
        ]);
        $senderIds = is_array($validated['selected_senders'])
            ? $validated['selected_senders']
            : explode(',', $validated['selected_senders']);
        Shipments::create(
            [
                'shipment_number' => $newShipmentNumber, // Example shipment number, you can customize this
                'sender_id' => $senderIds, // ✅ FIX
                'created_at' => $validated['dispatch_date'], // Assuming you want to set the created_at to the dispatch dat
            ]
        );
        // Convert comma-separated string into array
        $senderIds = explode(',', $validated['selected_senders']);
        // Loop through each sender_id and create a dispatch record
        foreach ($senderIds as $senderId) {
            Dispatch::create([
                'sender_id'     => $senderId,
                'dispatch_by'   => $validated['dispatch_to'],    // 'dispatch_to' comes from form
                'dispatched_at' => $validated['dispatch_date'],  // 'dispatch_date' comes from form
                'status'        => 'dispatch',
            ]);
        }
        return redirect()->back()->with('success', 'Bulk dispatch saved successfully!');
    }

    public function airlineBulk(Request $request)
    {
    $lastShipment = Shipments::orderByDesc('id')->first();
        if ($lastShipment && Str::startsWith($lastShipment->shipment_number, 'PG')) {
            $lastNumber = (int) Str::replaceFirst('PG', '', $lastShipment->shipment_number);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 101; // starting point
        }
        $newShipmentNumber = 'PG' . $newNumber;
        // Validate request
        $validated = $request->validate([
            'dispatch_to' => 'required|string|max:255',
            'dispatch_date' => 'required|date',
            'shipment_ids' => 'required|array', // This should be an array
            'shipment_ids.*' => 'integer',      // Ensure each item is an integer
            'notes' => 'nullable|string',
        ]);
        Shipments::create([
            'shipment_number' => $newShipmentNumber,
            'sender_id' => $validated['shipment_ids'],
            // ✅ FIX
            'created_at' => $validated['dispatch_date'], // Assuming you want to set the created_at to the dispatch date
        ]);
        // Loop through the selected shipment IDs
        foreach ($validated['shipment_ids'] as $shipmentId) {
            Dispatch::create([
                'sender_id'     => $shipmentId,
                'dispatch_by'   => $validated['dispatch_to'],
                'dispatched_at' => $validated['dispatch_date'],
                'status'        => 'dispatch',
            ]);
        }
        return redirect()->back()->with('success', 'Shipments dispatched successfully!');
    }

    public function shipment()
    {
        $shipment = Shipments::all();
        return view('backend.dispatch.shipment', compact('shipment'));
    }


    public function shipment_details($id)
    {
        $shipment = Shipments::findOrFail($id);
        $senderIds = is_array($shipment->sender_id)
            ? $shipment->sender_id
            : json_decode($shipment->sender_id, true);
        // Fetch senders based on the IDs
        $senders = Sender::whereIn('id', $senderIds)->get();

        return view('backend.dispatch.shipment_details', compact('shipment', 'senders'));
    }
}
