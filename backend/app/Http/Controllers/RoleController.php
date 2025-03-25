<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    // Store a new role
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|unique:roles|max:255',
            // Add other necessary fields and validation rules
        ]);

        $role = Role::create($validatedData);
        return response()->json($role, 201);
    }

    // Get all roles
    public function index()
    {
        $roles = Role::all();
        return response()->json($roles);
    }

    // Delete a role
    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();
        return response()->json(null, 204);
    }
}
