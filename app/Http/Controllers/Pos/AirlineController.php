<?php

namespace App\Http\Controllers\Pos;

use Illuminate\Support\Facades\DB;
use App\Models\Payment;
use App\Models\Dispatch;
use App\Models\Shipments;
use App\Models\Customer;
use App\Models\Tracking;
use App\Models\Weight;
use App\Models\Sender;
use App\Models\Agencies;
use App\Models\Airlines;
use Illuminate\Support\Str;



use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AirlineController extends Controller
{
    public function index()
    {
        $agencyPayments = DB::table('dispatch as d')
            ->leftJoin('shipments as s', 'd.sender_id', '=', 's.sender_id')
            ->select([
                'd.dispatch_by as agency_name',
                DB::raw("SUM(
            CASE 
                WHEN s.actual_weight LIKE '%Total Weight:%Kg%' 
                THEN CAST(
                    TRIM(REPLACE(SUBSTRING_INDEX(SUBSTRING_INDEX(s.actual_weight, 'Total Weight:', -1), 'Kg', 1), ' ', ''))
                    AS DECIMAL(10,2)
                ) 
                ELSE 0 
            END
        ) as total_weight"),
                DB::raw('COUNT(DISTINCT d.sender_id) as shipment_count'),
            ])
            ->groupBy('d.dispatch_by')
            ->orderByDesc('total_weight')
            ->get();



        return view(' backend.airlines.index', compact('agencyPayments', ));
    }


    public function create (Request $request){
       Airlines::create([
            'name'   => $request->name,
           ' created_at' => now()
          
        ]);

        return redirect()->back()->with('Successfully Added ');

    }



 public function airline_dispatch()
    {
        // Fetch all airlines
        $airlines = Airlines::all();
            $senders = Sender::with('receiver', 'dispatch','boxes','shipments')
        ->withCount('boxes')
        ->withSum('boxes','box_weight')
            ->doesntHave('dispatch') // Filter senders who do not have any payments
            ->get();

        return view('backend.airlines.airlines_dispatch', compact('senders' , 'airlines')); // Return the index view with tracking data
    }



      public function airlineBulkDispatch(Request $request)
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
        return redirect()->back()->with('success', 'Shipments dispatched successfully!');
    }






    public function shipment()
    {
        $shipment = Shipments::all();
        return view('backend.airlines.shipment', compact('shipment'));
    }
    public function payment()
    {
        $payments = Payment::all();
        return view('backend.airlines.payment', compact('payments'));
    }









    public function show($agency_name)
    {
        $senders = Sender::with('receiver', 'dispatch', 'payments')
            ->whereHas('dispatch', function ($query) use ($agency_name) {
                $query->where('dispatch_by', $agency_name);
            })
            ->get();

        if ($senders->isEmpty()) {
            return redirect()->back()->with('error', 'No senders found for this agency.');
        }

        return view('backend.agencies.preview', compact('senders'));
    }

    //
}
