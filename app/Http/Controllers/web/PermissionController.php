<?php

namespace App\Http\Controllers\web;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
class PermissionController extends Controller
{
    public function index()
    {
        // Fetch roles from the database and pass them to the view
        $roles = Role::with('permissions')->get();
       

        return view('Roles.index', compact('roles'));
    }

    public function edit($roleId)
    {
        // Retrieve the role and its associated users and permissions
        $role = Role::findOrFail($roleId);
        $usersWithRole = $role->users;
        $permissions = Permission::all();

        return view('Roles.edit', compact('role', 'usersWithRole', 'permissions'));
    }
    public function updatePermissions(Request $request, $roleId)
    {
        // Retrieve the role by its ID
        $role = Role::findOrFail($roleId);

        // Sync the permissions with the submitted values
        $role->permissions()->sync($request->input('permissions', []));

        return back()->with('success', 'Permissions updated successfully');
    }
}
