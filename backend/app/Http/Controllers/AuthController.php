<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail; // Import Mail facade
use App\Mail\CustomEmailVerification; // Import your custom email verification mailable

class AuthController extends Controller
{
    // Registration method
    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'email_verified_at' => null, // Ensure the email_verified_at is null on registration
                'verification_token' => null, // Initialize verification_token
            ]);

            // Generate a verification token
            $verificationToken = hash('sha256', $user->email . now());
            $user->verification_token = $verificationToken;
            $user->save();

            // Send the custom email verification notification
            Mail::to($user->email)->send(new CustomEmailVerification($user, $verificationToken));

            return response()->json([
                'message' => 'User registered successfully. Please check your email to verify your account.',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ],
            ], 201); // HTTP status code 201 for created
        } catch (\Exception $e) {
            Log::error('Registration error: ' . $e->getMessage());
            return response()->json(['message' => 'Registration failed', 'error' => $e->getMessage()], 500);
        }
    }

    // Login method
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]);

            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                throw ValidationException::withMessages([
                    'email' => ['The provided credentials are incorrect.'],
                ]);
            }

            // Check if the user's email is verified
            if (!$user->email_verified_at) {
                return response()->json(['message' => 'Email not verified. Please check your email.'], 403);
            }

            // Generate and return the access token
            $token = $user->createToken('YourAppName')->plainTextToken;

            Log::info('Login successful for user ID: ' . $user->id); // Log successful login

            return response()->json([
                'token' => $token, // Return the access token
                'message' => 'Login successful',
                'user' => [
                    'id' => $user->id, // Return user ID
                    'name' => $user->name,
                    'email' => $user->email,
                ],
            ], 200); // HTTP status code 200 for OK
        } catch (\Exception $e) {
            Log::error('Login error: ' . $e->getMessage());
            return response()->json(['message' => 'Login failed', 'error' => $e->getMessage()], 500);
        }
    }

    // Verify email method
    public function verifyEmail(Request $request, $token)
    {
        $user = User::where('verification_token', $token)->first();

        if (!$user) {
            return response()->json(['message' => 'Invalid verification token.'], 404);
        }

        $user->email_verified_at = now(); // Set the email_verified_at timestamp
        $user->verification_token = null; // Clear the verification token
        $user->save();

        Log::info('User verified:', ['user_id' => $user->id, 'email_verified_at' => $user->email_verified_at]);

        return response()->json(['message' => 'Email verified successfully.']);
    }
}
