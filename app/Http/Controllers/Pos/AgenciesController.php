<?php

namespace App\Http\Controllers\Pos;

use Illuminate\Support\Facades\DB;
use App\Models\Payment;
use App\Models\Dispatch;
use App\Models\Shipment;
use App\Models\Customer;
use App\Models\Tracking;
use App\Models\Weight;
use App\Models\Sender;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AgenciesController extends Controller
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



        return view(' backend.agencies.index', compact('agencyPayments', ));
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
