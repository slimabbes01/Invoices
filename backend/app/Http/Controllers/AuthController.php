<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // Method to handle user registration
    public function register(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed', // Ensure password confirmation
        ]);

        // Create a new user and hash the password
        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'profil' => null,
            'direction' => null,
            'isactive' => true,
            'image' => null,
            'phone' => null,
            'created_by' => 1, // Set this to the actual creator's ID, adjust as necessary
        ]);

        // Return the created user (or any relevant response)
        return response()->json(['user' => $user], 201); // 201 Created
    }

    // Method to handle user login
    public function login(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Attempt to find the user by username
        $user = User::where('username', $request->username)->first();

        // Check if user exists and password is correct
        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'username' => ['Les identifiants fournis sont incorrects.'], // Message in French
            ]);
        }

        // Create a new token for the user
        $token = $user->createToken('token')->plainTextToken;

        // Return the token and user information to the client
        return response()->json([
            'token' => $token,
            'username' => $user->username, // Return username for frontend use
            'name' => $user->name // Return name for frontend use
        ]);
    }
}
