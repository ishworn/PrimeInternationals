<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\Sender;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    // public function index()
    // {
    //     // Get all users with the role 'vendor' along with their senders and receivers
    //     $vendors = User::role('vendor') // Spatie Role
    //         ->with(['senders.receiver']) // Eager load senders and their receivers
    //         ->get();

    //     return view('backend.vendor.index', compact('vendors'));
    // }

    public function index($id = null)
    {
        if ($id) {
            // Get specific vendor with their senders and receivers
            $vendors = User::role('vendor')
                ->where('id', $id)
                ->with(['senders.receiver'])
                ->get();
        } else {
            // Get all vendors
            $vendors = User::role('vendor')
                ->with(['senders.receiver'])
                ->get();
        }

        return view('backend.vendor.index', compact('vendors'));
    }


    public function show()
    {
        $vendors = User::role('vendor')
            ->withCount('senders') // Counts related senders
            ->get();

        return view('backend.vendor.show', compact('vendors'));
    }
}
