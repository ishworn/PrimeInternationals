<?php


namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use App\Models\Agencies;
use App\Models\Sender;
use App\Models\Dispatch;
use Illuminate\Http\Request;
use App\Models\Shipments;
use App\Models\Airlines;

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
