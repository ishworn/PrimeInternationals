<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Sender;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use App\Models\Dispatch;



use App\Models\InvoiceDetail;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index()
    {
        $totalIncome = Payment::sum('total_paid');
        $totalCustomer = Sender::count();
        $totalUser = User::count();
        $totalDispatch = Dispatch::count();

        $pendingDispatch = $totalCustomer-$totalDispatch;



        // 1. Get all senders
        $senders = DB::table('senders')->pluck('id');
        // 2. Get each sender's payment status
        $paymentStatuses = DB::table('payments')
            ->select('sender_id', 'status')
            ->pluck('status', 'sender_id');
        // 3. Initialize counters
        $counts = [
            'completed' => 0,
            'partial' => 0,
            'pending' => 0,
        ];
        // 4. Count status per sender
        foreach ($senders as $senderId) {
            if (!isset($paymentStatuses[$senderId])) {
                $counts['pending']++; // No payment → pending
            } else {
                $status = $paymentStatuses[$senderId];
                if (isset($counts[$status])) {
                    $counts[$status]++;
                }
            }
        }


        // For Bar Chart
        $agencyWeights = DB::table('dispatch as d')
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
            ])
            ->groupBy('d.dispatch_by')
            ->orderByDesc('total_weight')
            ->pluck('total_weight', 'agency_name'); // ← key-value pair


        // pi chart


        $parcelCounts = DB::table('dispatch')
            ->select('dispatch_by as agency_name', DB::raw('COUNT(*) as parcel_count'))
            ->groupBy('dispatch_by')
            ->orderByDesc('parcel_count')
            ->pluck('parcel_count', 'agency_name');







        return view('admin.index', [
            'totalIncome' => $totalIncome,
            'totalCustomer' => $totalCustomer,
            'totalUser' => $totalUser,
            'pendingDispatch' => $pendingDispatch,
            'chartLabels' => array_keys($counts),
            'chartValues' => array_values($counts),
            'barchartLabels' => $agencyWeights->keys()->toArray(),
            'barchartValues' => $agencyWeights->values()->toArray(),
            'pichartLabels' => $parcelCounts->keys(),
            'pichartValues' => $parcelCounts->values(),
        ]);
    }

    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        $notification = array(
            'message' => 'User Logout Successfully',
            'alert-type' => 'success'
        );

        return redirect('/login')->with($notification);
    } // End Method 


    public function Profile()
    {
        $id = Auth::user()->id;
        $adminData = User::find($id);
        return view('admin.admin_profile_view', compact('adminData'));
    } // End Method 

    public function EditProfile()
    {

        $id = Auth::user()->id;
        $editData = User::find($id);
        return view('admin.admin_profile_edit', compact('editData'));
    } // End Method 

    public function StoreProfile(Request $request)
    {
        $id = Auth::user()->id;
        $data = User::find($id);
        $data->name = $request->name;
        $data->email = $request->email;
        $data->username = $request->username;

        if ($request->file('profile_image')) {
            $file = $request->file('profile_image');

            $filename = date('YmdHi') . $file->getClientOriginalName();
            $file->move(public_path('upload/admin_images'), $filename);
            $data['profile_image'] = $filename;
        }
        $data->save();

        $notification = array(
            'message' => 'Admin Profile Updated Successfully',
            'alert-type' => 'info'
        );

        return redirect()->route('admin.profile')->with($notification);
    } // End Method


    public function ChangePassword()
    {

        return view('admin.admin_change_password');
    } // End Method


    public function UpdatePassword(Request $request)
    {

        $validateData = $request->validate([
            'oldpassword' => 'required',
            'newpassword' => 'required',
            'confirm_password' => 'required|same:newpassword',

        ]);

        $hashedPassword = Auth::user()->password;
        if (Hash::check($request->oldpassword, $hashedPassword)) {
            $users = User::find(Auth::id());
            $users->password = bcrypt($request->newpassword);
            $users->save();

            session()->flash('message', 'Password Updated Successfully');
            return redirect()->back();
        } else {
            session()->flash('message', 'Old password is not match');
            return redirect()->back();
        }
    } // End Method



}
