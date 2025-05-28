<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\Sender;
use App\Mail\TrackingUpdated;
use Illuminate\Support\Facades\Mail;


class TrackingController extends Controller
{
    // Display all trackings


    public function index()
    {
        $senders = Sender::with('receiver', 'dispatch')
            ->whereHas('dispatch', function ($query) {
                $query->whereNotNull('dispatch_by'); // Ensure 'dispatch_by' has a value, indicating dispatch is done
            })
            ->whereNull('trackingId') // Exclude records where trackingId is not null
            ->get();

        // Fetch all tracking records
        return view('backend.trackings.index', compact('senders')); // Return the index view with tracking data
    }




    // Show form to edit an existing tracking record
    public function edit($id)
    {
        $sender = Sender::with('receiver')->findOrFail($id); // Find the tracking record by ID
        return view('backend.trackings.edit', compact('sender')); // Return the edit view with the tracking data
    }

    // Update an existing tracking record
    public function update(Request $request, $id)
    { // Find the tracking record by ID
        $sender = Sender::with('receiver')->findOrFail($id);

        // Validate the updated input data
        $sender->senderEmail = $request->input('sender_email');
        $sender->trackingId = $request->input('tracking_number');
        $sender->receiver->receiverEmail = $request->input('receiver_email');

        // Update the tracking record in the database
        $sender->save();
        $sender->receiver->save();
        Mail::to($sender->senderEmail)->send(new TrackingUpdated($sender, $sender->receiver));
        Mail::to($sender->receiver->receiverEmail)->send(new TrackingUpdated($sender, $sender->receiver));
        // Redirect back to the index route after successful update
        return redirect()->route('trackings.index')->with('success', 'Tracking record updated!');
    }

    public function status()
    {
        // $sender = Sender::with('receiver')->get();
        $sender = Sender::with('receiver', 'dispatch')
            ->where('status', '!=', 'success') // Exclude senders with status = success
            ->whereNotNull('trackingId') // Only those with trackingId set
            ->get();

            

        return view('backend.trackings.parcel_status', compact('sender'));
    }

    public function updateStatus(Request $request, $id)
    {
        $sender = Sender::findOrFail($id);
        $sender->status = $request->status;
        $sender->save();

        return back()->with('success', 'Sender status updated successfully.');
    }
}
