<?php
namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use App\Models\Tracking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrackingController extends Controller
{
    // Display all trackings
    public function index()
    {
        $trackings = Tracking::all(); // Fetch all tracking records
        return view('backend.trackings.index', compact('trackings')); // Return the index view with tracking data
    }

    // Show form to create a new tracking record
    public function create()
    {
        return view('backend.trackings.create'); // Create a form for adding a new tracking
    }

    // Store a new tracking record
    public function store(Request $request)
    {
        // Validate the input data
        $validated = $request->validate([
            'tracking_number' => 'required|unique:trackings',
            'receiver_name' => 'required',
            'location' => 'required',
        ]);

        // Store the validated data in the database
        Tracking::create($validated);

        // Redirect to the index page after successful submission
           return redirect()->route('trackings.index')->with('success', 'Tracking created successfully!');
    }

    // Show form to edit an existing tracking record
    public function edit($id)
    {
        $tracking = Tracking::findOrFail($id); // Find the tracking record by ID
        return view('backend.trackings.edit', compact('tracking')); // Return the edit view with the tracking data
    }

    // Update an existing tracking record
    public function update(Request $request, $id)
    {
        // Find the tracking record by ID
        $tracking = Tracking::findOrFail($id);

        // Validate the updated input data
        $validated = $request->validate([
            'tracking_number' => 'required|unique:trackings,tracking_number,' . $tracking->id,
            'receiver_name' => 'required',
            'location' => 'required',
        ]);

        // Update the tracking record in the database
        $tracking->update($validated);

        // Redirect back to the index route after successful update
        return redirect()->route('trackings.index')->with('success', 'Tracking record updated!');
        
    }
}
