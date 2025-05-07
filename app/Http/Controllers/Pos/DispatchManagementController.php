<?php


namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;


use App\Models\Sender;
use App\Models\Dispatch;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;



class DispatchManagementController extends Controller
{
    public function index()
    {
        $senders = Sender::with('receiver', 'dispatch', )
        ->doesntHave('dispatch') // Filter senders who do not have any payments
        ->get();
 

  
  

         // Fetch all tracking records
        return view('backend.dispatch.index', compact( 'senders'   )); // Return the index view with tracking data
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

   

  

}


