<?php


namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;

use App\Models\User;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;



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
   
    try {
        // Validate input
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],


            
        ]);

        // Create the user
        $user = User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // Assign the role
        $user->assignRole($request->input('role'));

        // Redirect on success
        return redirect('/usermgmt')->with('success', 'User created successfully.');
    } catch (\Exception $e) {
        // Log the error (optional)
        Log::error('User creation failed: ' . $e->getMessage());

        // Redirect back with error
        return redirect()->back()->withInput()->withErrors(['error' => 'Failed to create user. ' . $e->getMessage()]);
    }
}


    public function UserDetailsShow()
    {

        // Fetch each user data
        $users = User::all();
        $roles = \Spatie\Permission\Models\Role::all(); // Fetch all roles

         // Fetch and redirect all user data
        return view('backend.usermgmt.index', compact( 'users' , 'roles'  )); // Return the index view with tracking data
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


