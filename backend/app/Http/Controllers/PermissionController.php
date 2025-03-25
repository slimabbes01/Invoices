<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    // Store a new permission
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|unique:permissions|max:255',
            // Add other necessary fields and validation rules
        ]);

        $permission = Permission::create($validatedData);
        return response()->json($permission, 201);
    }

    // Get all permissions
    public function index()
    {
        $permissions = Permission::all();
        return response()->json($permissions);
    }

    // Delete a permission
    public function destroy($id)
    {
        $permission = Permission::findOrFail($id);
        $permission->delete();
        return response()->json(null, 204);
    }
}
