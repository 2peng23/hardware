<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AdminController extends Controller
{
    public function adminUsers()
    {
        // $current_user = Auth::user();
        // $users = User::where('email', '!=', $current_user->email)
        //     ->where('status', 'active')
        //     ->get();
        return view('admin.users');
    }

    public function fetchUser()
    {
        $current_user = Auth::user();
        $users = User::where('email', '!=', $current_user->email)
            ->where('status', 'active')
            ->get();
        // return view('admin.users', compact('users'));
        return response([
            'users' => $users,
        ]);
    }
    public function fetchInactiveUser()
    {
        $users = User::where('status', 'inactive')->get();
        return response([
            'users' => $users,
        ]);
    }
    public function inactiveUser()
    {
        return view('admin.inactive');
    }
    public function addUser(Request $request)
    {
        // validate the user
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);
        // create the user and save to database
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->status = 'active';
        $user->email_verified_at = now();
        $user->password = $request->password;
        $user->usertype = $request->usertype;
        $user->designation = $request->designation;
        $user->save();
        return response()->json([
            'success' => 'New user created successfully.'
        ]);
    }

    public function deactUser($id)
    {
        $user = User::find($id); // Use find instead of where to get the user by its ID

        if (!$user) {
            return response()->json([
                'error' => 'User not found'
            ], 404); // Return an error response with status code 404 if the user is not found
        }

        $user->status = 'inactive';
        $user->save();

        return response()->json([
            'success' => 'User deactivated successfully!'
        ], 200); // Return a success response with status code 200
    }

    public function activateUser($id)
    {
        $user = User::find($id); // Use find instead of where to get the user by its ID

        if (!$user) {
            return response()->json([
                'error' => 'User not found'
            ], 404); // Return an error response with status code 404 if the user is not found
        }

        $user->status = 'active';
        $user->save();

        return response()->json([
            'success' => 'User activated successfully!'
        ], 200); // Return a success response with status code 200
    }

    public function deleteUser($id)
    {
        $user = User::find($id); // Use find instead of where to get the user by its ID

        if (!$user) {
            return response()->json([
                'error' => 'User not found'
            ], 404); // Return an error response with status code 404 if the user is not found
        }

        $user->delete();

        return response()->json([
            'success' => 'User deleted successfully!'
        ], 200); // Return a success response with status code 200
    }

    public function editUser($id)
    {
        $user = User::find($id); // Use find instead of where to get the user by its ID
        return response()->json([
            'user' => $user,
        ]); // Return a success response with status code 200
    }

    public function updateUser(Request $request)
    {

        $user_id = $request->item_id;
        $user = User::find($user_id);
        $user->name = $request->name;
        $user->designation = $request->designation;
        $user->email = $request->email;
        $user->status = 'active';
        $user->password = $request->password;
        $user->usertype = $request->usertype;
        $user->update();
        return response()->json([
            'success' => 'User updated successfully!'
        ], 200); // Return a success response with status code 200
    }
}
