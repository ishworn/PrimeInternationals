<?php
namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\Sender;
use App\Mail\TrackingUpdated;
use Illuminate\Support\Facades\Mail;
use App\Models\Payment;
use App\Models\Shipment;

class PaymentController extends Controller
{
    // Display all trackings


    public function index()
    {
        $senders = Sender::with('receiver', 'payments', 'shipments', 'boxes')
        ->doesntHave('payments') // Filter senders who do not have any payments
        ->get();
        $senderBoxCounts = [];

    // Loop through each sender and count their boxes
    foreach ($senders as $sender) {
        $senderBoxCounts[$sender->id] = $sender->boxes->count(); // Count boxes for each sender
    }
  

         // Fetch all tracking records
        return view('backend.payments.index', compact( 'senders' , 'senderBoxCounts' )); // Return the index view with tracking data
    }




    public function filterPayments(Request $request)
    {
        // Get filter parameters from the request
        $filter = $request->input('filter', 'day'); // Default filter: day
        $date = $request->input('date', now()->format('Y-m-d')); // Default date: today

        // Initialize variables
        $payments = [];
        $totalIncome = 0;

        // Fetch payments based on the filter
        switch ($filter) {
            case 'day':
                $payments = Payment::whereDate('created_date', $date)->get();
                $totalIncome = $payments->sum('amount');
                break;

            case 'week':
                $startOfWeek = now()->startOfWeek()->format('Y-m-d');
                $endOfWeek = now()->endOfWeek()->format('Y-m-d');
                $payments = Payment::whereBetween('created_date', [$startOfWeek, $endOfWeek])->get();
                $totalIncome = $payments->sum('amount');
                break;

            case 'month':
                $month = now()->format('Y-m');
                $payments = Payment::whereYear('created_date', now()->year)
                    ->whereMonth('created_date', now()->month)
                    ->get();
                $totalIncome = $payments->sum('amount');
                break;

            case 'year':
                $year = now()->year;
                $payments = Payment::whereYear('created_date', $year)->get();
                $totalIncome = $payments->sum('amount');
                break;
        }

        // Fetch monthly and yearly totals for graphs
        $monthlyTotals = Payment::selectRaw('YEAR(payment_date) as year, MONTH(payment_date) as month, SUM(amount) as total')
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        $yearlyTotals = Payment::selectRaw('YEAR(payment_date) as year, SUM(amount) as total')
            ->groupBy('year')
            ->orderBy('year', 'asc')
            ->get();

        return view('backend.payments.details', compact('payments', 'totalIncome', 'filter', 'date', 'monthlyTotals', 'yearlyTotals'));
    }
    public function details()
    {
    
        $today = now()->format('Y-m-d');

        // Fetch payments for today
        $payments = Payment::whereDate('created_at', $today)->get();
  

         // Fetch all tracking records
        return view('backend.payments.details', compact( 'payments' ,  )); // Return the index view with tracking data
    }

    public function store(Request $request)
    {
        // dd($request->all());
        // Validate the request
        $request->validate([
            'sender_id' => 'required|exists:senders,id',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string',
            
        ]);
        // dd($request);

        // Create a new payment record
        Payment::create([
            'sender_id' => $request->sender_id,
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'status' => 'paid',
            
        ]);

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Payment saved successfully!');
    }
  


   
   
}
