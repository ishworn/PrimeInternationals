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
    
        $senders = Sender::with('receiver')
                     ->whereNull('trackingId')  // Only select rows where trackingId is null
                     ->get();
  

         // Fetch all tracking records
        return view('backend.trackings.index', compact( 'senders' ,  )); // Return the index view with tracking data
    }

   

    // Show form to edit an existing tracking record
    public function edit($id)
    {
        $sender = Sender::with('receiver')->findOrFail($id); // Find the tracking record by ID
    // dd($sender);
        return view('backend.trackings.edit', compact('sender' )); // Return the edit view with the tracking data
    }

    // Update an existing tracking record
    public function update(Request $request, $id)
    {// Find the tracking record by ID
        $sender = Sender::findOrFail($id);
       
        // Validate the updated input data
        $sender->trackingId = $request->input('tracking_number'); 
        // Update the tracking record in the database
        $sender->save();
        Mail::to($sender->senderEmail)->send(new TrackingUpdated($sender, $sender->receiver));
        Mail::to($sender->receiver->receiverEmail)->send(new TrackingUpdated($sender, $sender->receiver));
        // Redirect back to the index route after successful update
        return redirect()->route('trackings.index')->with('success', 'Tracking record updated!');
        
    }
   
}
