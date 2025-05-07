<?php


namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;

use App\Models\User;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class UsermgmtController extends Controller
{
    public function index()
    {
        return view('backend.usermgmt.index');
    }

    public function create()
    {
        return view('backend.usermgmt.index');  // return the registration view
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Create the user if validation passes
        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Redirect to a page (such as dashboard) after successful registration
        return redirect('/usermgmt');
    }

    public function UserDetailsShow()
    {

        // Fetch each user data
        $users = User::all();

         // Fetch and redirect all user data
        return view('backend.usermgmt.index', compact( 'users' ,  )); // Return the index view with tracking data
    }


    public function destroy($id)
    {
        // Find the user by ID
        $user = User::findOrFail($id);

        // Delete the user
        $user->delete();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'User deleted successfully.');
    }

}


