<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\Sender;
use App\Mail\TrackingUpdated;
use Illuminate\Support\Facades\Mail;
use App\Models\Payment;
use App\Models\Shipment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Expense;
use App\Models\Billing;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Mail\InvoiceMail;
use Exception;

class PaymentController extends Controller
{
    // Display all trackings


    public function index()
    {
        $senders = Sender::with('receiver', 'payments', 'shipments', 'boxes')
            ->where(function ($query) {
                $query->whereDoesntHave('payments')
                    ->orWhereHas('payments', function ($query) {
                        $query->where('status', 'partial');
                    });
            })
            ->get();

        $totalWeights = [];

        foreach ($senders as $sender) {
            $actualWeightString = $sender->shipments->actual_weight ?? '';
            $totalWeights[$sender->id] = $this->extractTotalWeightValue($actualWeightString);
        }

        return view('backend.payments.index', compact('senders', 'totalWeights'));
    }
    public function extractTotalWeightValue($string)
    {
        if (preg_match('/Total\s*Weight\s*:\s*([\d\.]+)Kg/i', $string, $matches)) {
            return (float) $matches[1];
        }
        return null;
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
                $totalIncome = $payments->sum('total_paid');
                break;

            case 'week':
                $startOfWeek = now()->startOfWeek()->format('Y-m-d');
                $endOfWeek = now()->endOfWeek()->format('Y-m-d');
                $payments = Payment::whereBetween('created_date', [$startOfWeek, $endOfWeek])->get();
                $totalIncome = $payments->sum('total_paid');
                break;

            case 'month':
                $month = now()->format('Y-m');
                $payments = Payment::whereYear('created_date', now()->year)
                    ->whereMonth('created_date', now()->month)
                    ->get();
                $totalIncome = $payments->sum('total_paid');
                break;

            case 'year':
                $year = now()->year;
                $payments = Payment::whereYear('created_date', $year)->get();
                $totalIncome = $payments->sum('total_paid');
                break;
        }

        // Fetch monthly and yearly totals for graphs
        $monthlyTotals = Payment::selectRaw('YEAR(payment_date) as year, MONTH(payment_date) as month, SUM(total_paid) as total')
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        $yearlyTotals = Payment::selectRaw('YEAR(payment_date) as year, SUM(total_paid) as total')
            ->groupBy('year')
            ->orderBy('year', 'asc')
            ->get();










        return view('backend.payments.details', compact('payments', 'totalIncome', 'filter', 'date', 'monthlyTotals', 'yearlyTotals',));
    }
    public function details()
    {
        $expenses = Expense::all();


        // Fetch all tracking records
        return view('backend.payments.details', compact('expenses')); // Return the index view with tracking data
    }

    public function edit(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);
        $payBy = $request->input('pay_by');
        $newPay = 0;
        $cash = (float) $request->input('pay_cash', 0);
        $bank = (float) $request->input('pay_bank', 0);
        $newPay = $cash + $bank;
        // Update amounts
        $payment->total_paid += $newPay;
        $payment->status = $payment->total_paid >= $payment->bill_amount ? 'completed' : 'partial';

        if ($payment->payment_method == $payBy) // Update payment method
        {
            $payment->payment_method = $payBy;
        } else {
            $payment->payment_method = 'both';
        }

        // Update cash and bank values based on payment method
        if ($payBy === 'cash') {
            $payment->cash_amount += $newPay;
        } elseif ($payBy === 'bank') {
            $payment->bank_amount += $newPay;
        } elseif ($payBy === 'both') {
            $payment->cash_amount += $cash;
            $payment->bank_amount += $bank;
        }

        $payment->save();

        return redirect()->back()->with('success', 'Payment updated successfully.');
    }


    public function store(Request $request)
    {


        try {
            // Calculate total paid
            $totalPaid = 0;
            if ($request->payment_method === 'Cash') {
                $totalPaid = $request->cash_amount;
                $payment_method = 'cash';
            } elseif ($request->payment_method === 'Bank Transfer') {
                $totalPaid = $request->bank_amount;
                $payment_method = 'bank_transfer';
            } else {
                $totalPaid = $request->cash_amount + $request->bank_amount;
                $payment_method = 'both';
            }

            // Find the payment record and update
            $payment = Payment::where('sender_id', $request->sender_id); // Assuming you want to update a payment by sender_id

            // If payment exists, update it, otherwise create a new payment record

            $payment->update([
                'bill_amount' => $request->bill_amount,
                'sender_id' => $request->sender_id,
                'payment_method' => $payment_method,
                'cash_amount' => $request->cash_amount ?? 0,
                'bank_amount' => $request->bank_amount ?? 0,
                'total_paid' => $totalPaid,
                'status' => $totalPaid >= $request->bill_amount ? 'completed' : 'partial',
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Payment failed: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Payment recorded successfully');
    }



    // public function manage()
    // {
    //     $senders = Sender::with('receiver', 'dispatch', 'payments') // Include 'payments' relation
    //         ->whereHas('dispatch') // Only fetch senders who have dispatch records
    //         ->whereHas('payments', function ($query) {
    //             $query->whereNull('debits'); // Only fetch payments where debits is null
    //         })
    //         ->get();


    //     $totalWeights = [];

    //     foreach ($senders as $sender) {
    //         $actualWeightString = $sender->shipments->actual_weight ?? '';
    //         $totalWeights[$sender->id] = $this->extractTotalWeightValue($actualWeightString);
    //     }



    //     return view('backend.payments.manage', compact('senders', 'totalWeights'));
    // }

    // public function debits(Request $request)
    // {

    //     // Find the payment record associated with the sender
    //     $payment = Payment::where('sender_id', $request->sender_id)->first();
    //     // dd($payment);
    //     // 
    //     if (!$payment) {
    //         return redirect()->back()->with('error', 'Payment record not found for this sender.');
    //     }
    //     // Update the debits column
    //     $payment->debits = $request->debits;
    //     $payment->paymethod_debits = $request->paymethod_debits; // Update the payment method for debits
    //     $payment->save();
    //     // Redirect back with success message
    //     return redirect()->back()->with('success', 'Debit amount updated successfully.');
    // }

    public function dashboard()
    {
        $today = now()->format('Y-m-d');
        $currentDate = now();

        // Daily Payments
        $payments = Payment::whereDate('created_at', $today)->get();
        $totalcash = $payments->sum('cash_amount');
        $totalBank_transfer = $payments->sum('bank_amount');

        // Weekly Payments
        $startOfWeek = $currentDate->copy()->startOfWeek(Carbon::SUNDAY);
        $endOfWeek = $currentDate->copy()->endOfWeek(Carbon::SATURDAY);

        $weeklyPayments = Payment::whereBetween('created_at', [$startOfWeek, $endOfWeek])->get();
        $weeklyCash = $weeklyPayments->sum('cash_amount');
        $weeklyBankTransfer = $weeklyPayments->sum('bank_amount');
        $weeklyTotal = $weeklyCash + $weeklyBankTransfer;

        // Monthly Payments
        $startOfMonth = $currentDate->copy()->startOfMonth();
        $endOfMonth = $currentDate->copy()->endOfMonth();

        $monthlyPayments = Payment::whereBetween('created_at', [$startOfMonth, $endOfMonth])->get();
        $monthlyCash = $monthlyPayments->sum('cash_amount');
        $monthlyBankTransfer = $monthlyPayments->sum('bank_amount');
        $monthlyTotal = $monthlyCash + $monthlyBankTransfer;

        // Date Ranges for display
        $weekRange = $startOfWeek->format('M j') . ' - ' . $endOfWeek->format('M j, Y');
        $monthRange = $startOfMonth->format('M Y');

        // Fetch senders with relationships and payments
        $sendersAll = Sender::with('receiver', 'dispatch', 'payments')
            ->whereHas('dispatch')
            ->whereHas('payments')
            ->get();

        // Agency Payments Report
        $agencyPayments = DB::table('payments as p')
            ->join('dispatch as d', 'p.sender_id', '=', 'd.sender_id')
            ->leftJoin('shipments as s', 'p.sender_id', '=', 's.sender_id')
            ->select([
                'd.dispatch_by as agency_name',
                DB::raw('SUM(p.total_paid) as total_received'),
                DB::raw('SUM(p.debits) as total_debits'),
                DB::raw('SUM(p.total_paid - p.debits) as profit'),
                DB::raw('COUNT(DISTINCT p.id) as payment_count'),
                DB::raw('ROUND((SUM(p.total_paid - p.debits) / SUM(p.total_paid)) * 100, 2) as profit_margin'),
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
            ])
            ->groupBy('d.dispatch_by')
            ->orderByDesc('profit')
            ->get();

        // Financial Calculations (from beginning of year to today)
        $startOfYear = Carbon::parse($today)->startOfYear();
        $endOfToday = Carbon::parse($today)->endOfDay();
        $date = $today;

        $yearlyPayments = Payment::whereBetween('created_at', [$startOfYear, $endOfToday]);


        // dd($yearlyPayments);
        $cash_receive = $yearlyPayments->sum('cash_amount');
        $bank_receive = $yearlyPayments->sum('bank_amount');
        //  dd($bank_receive);
        $receiveAmount = $yearlyPayments->sum('total_paid');
        $debits = $yearlyPayments->sum('debits');


        $cashdebt = Payment::where('paymethod_debits', 'Cash')->sum('debits');
        $bankdebt = Payment::where('paymethod_debits', 'Bank')->sum('debits');

        $totalCashPaid = Expense::where('payment_method', 'Cash')->sum('amount');
        $totalBankPaid = Expense::where('payment_method', 'Bank')->sum('amount');
        $totalExpenses = Expense::whereBetween('date', [$startOfYear, $date])->sum('amount');

        // Calculated Balances
        $totalIncomeCash = $cash_receive - $cashdebt;
        $totalIncomeBank = $bank_receive - $bankdebt;
        $totalIncome = $receiveAmount - $debits;

        $openingBalanceCash = $totalIncomeCash - $totalCashPaid;
        $openingBalanceBank = $totalIncomeBank - $totalBankPaid;
        $openingBalance = $totalIncome - $totalExpenses;

        // Today's Income
        $todaysIncome = $payments;





        $yesterday = Carbon::parse($today)->subDay();
        $startOfYear = Carbon::parse($today)->startOfYear(); // already exists
        $endOfYesterday = $yesterday->copy()->endOfDay();

        $yesterdayPayments = Payment::whereBetween('created_at', [$startOfYear, $endOfYesterday]);
        $yesterdayCashReceive = $yesterdayPayments->sum('cash_amount');
        $yesterdayBankReceive = $yesterdayPayments->sum('bank_amount');
        $yesterdayReceiveAmount = $yesterdayPayments->sum('total_paid');
        $yesterdayDebits = $yesterdayPayments->sum('debits');

        $yesterdayCashDebts = Payment::where('paymethod_debits', 'Cash')
            ->whereBetween('created_at', [$startOfYear, $endOfYesterday])
            ->sum('debits');

        $yesterdayBankDebts = Payment::where('paymethod_debits', 'Bank')
            ->whereBetween('created_at', [$startOfYear, $endOfYesterday])
            ->sum('debits');

        $yesterdayCashExpenses = Expense::where('payment_method', 'Cash')
            ->whereBetween('date', [$startOfYear, $endOfYesterday])
            ->sum('amount');

        $yesterdayBankExpenses = Expense::where('payment_method', 'Bank')
            ->whereBetween('date', [$startOfYear, $endOfYesterday])
            ->sum('amount');

        $yesterdayTotalExpenses = $yesterdayCashExpenses + $yesterdayBankExpenses;


        $yesterdayIncomeCash = $yesterdayCashReceive - $yesterdayCashDebts;
        $yesterdayIncomeBank = $yesterdayBankReceive - $yesterdayBankDebts;
        $yesterdayTotalIncome = $yesterdayReceiveAmount - $yesterdayDebits;

        $yesterdayClosingCash = $yesterdayIncomeCash - $yesterdayCashExpenses;
        $yesterdayClosingBank = $yesterdayIncomeBank - $yesterdayBankExpenses;
        $yesterdayClosingBalance = $yesterdayTotalIncome - $yesterdayTotalExpenses;




        return view('backend.payments.dashboard', compact(
            'yesterdayClosingCash',
            'yesterdayClosingBank',
            'yesterdayClosingBalance',
            'cashdebt',
            'bankdebt',
            'totalcash',
            'totalBank_transfer',
            'weeklyCash',
            'weeklyBankTransfer',
            'weeklyTotal',
            'weekRange',
            'monthlyCash',
            'monthlyBankTransfer',
            'monthlyTotal',
            'monthRange',
            'sendersAll',
            'debits',
            'agencyPayments',
            'openingBalance',
            'openingBalanceCash',
            'openingBalanceBank',
            'totalExpenses',
            'todaysIncome',
            'date',
            'totalCashPaid',
            'totalBankPaid',
            'cash_receive',
            'bank_receive'
        ));
    }

    public function addexpenses(Request $request)
    {
        $request->validate([
            'expense_name'   => 'required|string|max:255',
            'amount'         => 'required|numeric|min:0',
            'date'           => 'required|date',
            'category'       => 'required|string',
            'other_category' => 'nullable|string|max:255',
            'payment_method' => 'required|in:Cash,Bank',
            'receipt'        => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle image upload
        $receiptPath = null;
        if ($request->hasFile('receipt')) {
            $receiptPath = $request->file('receipt')->store('receipts', 'public');
        }

        // Store data
        Expense::create([
            'expense_name'   => $request->expense_name,
            'amount'         => $request->amount,
            'date'           => $request->date,
            'category'       => $request->category,
            'other_category' =>  $request->other_category,
            'payment_method' => $request->payment_method,
            'receipt'        => $receiptPath,
        ]);



        return redirect()->back()->with('success', 'Expense added successfully!');
    }


    public function printInvoice($id)
    {
        // Load payment with related sender and receiver
        $sender = Sender::with('payments', 'receiver', 'boxes', 'shipments')->findOrFail($id); // Or use where if needed

        $totalBoxes = $sender->boxes->count(); // Count the number of boxes associated with the sender
        $totalweights = $sender->boxes->sum('box_weight'); // Sum the weight of all boxes associated with th



        // Safety check if payment or sender doesn't exist


        // Check if total_weight is null
        if (is_null($sender->shipments->actual_weight)) {
            // Redirect to add weight page (change the route name if needed)
            return view('backend.customer.customer_addweight', compact('sender'));
        }

        // Otherwise, show invoice page
        return view('backend.payments.invoice', compact('sender', 'totalBoxes', 'totalweights'));
    }

    public function InvoiceStore(Request $request)
    {


        try {
            $totalBillAmount = 0;

            foreach ($request->description as $index => $desc) {
                $itemTotal = $request->quantity[$index] * $request->rate[$index];
                $totalBillAmount += $itemTotal;

                $billing =   Billing::create([
                    'sender_id' => $request->sender_id,
                    'description' => $desc,
                    'quantity' => $request->quantity[$index],
                    'rate' => $request->rate[$index],
                    'total' => $itemTotal,
                ]);
            }
            $billing->save();
     
            $sender = Sender::with('payments', 'receiver', 'boxes', 'shipments',)->findOrFail($request->sender_id);
            $billings = Billing::where('sender_id', $request->sender_id)->get();
      
  Payment::create([
                'sender_id' => $request->sender_id,
                'bill_amount' => $totalBillAmount,
                'status' => 'partial'
            ]);
         

     

            try {
                $pdf = Pdf::loadView('invoices.pdf', compact('sender', 'billings'));
                $fileName = 'invoice_' . $sender->invoiceId . '.pdf';
                $pdfPath = storage_path('app/public/' . $fileName);
                $pdf->save($pdfPath);
            } catch (\Exception $e) {
                Log::error('PDF generation failed', [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
                return response()->json(['success' => false, 'message' => 'Failed to generate invoice PDF.']);
            }

            try {
                Mail::to($sender->senderEmail)->send(new InvoiceMail($sender,  $billings,  $pdfPath, [
                    'as' => $fileName,
                    'mime' => 'application/pdf',
                ]));
            } catch (\Exception $e) {
                Log::error('PDF generation failed', [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
                return response()->json(['success' => false, 'message' => 'Failed to generate invoice PDF.']);
            }
            return response()->json(['success' => true, 'message' => 'Invoice saved successfully!']);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong.',
                'error' => $e->getMessage(),
                'stack' => $e->getTraceAsString()  // You can include the stack trace for more details
            ], 500);
        }
    }
}
