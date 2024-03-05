<?php

namespace App\Http\Controllers\web;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $customerId = auth()->user()->customer_id;

        // Assuming there's a relationship between users and customers
        $users = User::where('customer_id', $customerId)->with('roles')->get();
    
        return view('User_role.users', compact('users'));
       
    }
    
    public function editUserRoles($userId)
    {
        // Retrieve the user and their roles
        $user = User::with('roles')->findOrFail($userId);
    
        // Retrieve all roles from the database
        $roles = Role::all();

            
        return view('User_role.edit_user', compact('user','roles'));
    }
    public function updateRoles(Request $request, $userId)
    {
        // Retrieve the user
        $user = User::findOrFail($userId);

        // Update the user's roles based on the submitted form data
        $user->roles()->sync($request->input('roles', []));

        // Redirect back with a success message
        return redirect()->back()->with('success', 'User roles updated successfully.');
    }
}
