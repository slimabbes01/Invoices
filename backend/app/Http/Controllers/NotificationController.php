<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    // Require authentication for all routes
    public function __construct()
    {
        $this->middleware('auth:sanctum'); // Ensure the user is authenticated using Sanctum
    }

    // Store a new notification
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'message' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id', // Ensure the user ID exists
            // Add other necessary fields and validation rules if needed
        ]);

        $notification = Notification::create($validatedData);
        return response()->json([
            'message' => 'Notification created successfully.',
            'notification' => $notification,
        ], 201);
    }

    // Get all notifications (with pagination)
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10); // Default to 10 notifications per page
        $notifications = Notification::where('user_id', $request->user()->id) // Fetch notifications for the authenticated user
        ->paginate($perPage);
        return response()->json($notifications);
    }

    // Delete a notification
    public function destroy($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->delete();
        return response()->json(null, 204);
    }

    // Automatically create a notification for invoice creation
    public function createInvoiceNotification($userId, $invoiceTitle)
    {
        $notificationData = [
            'message' => "Invoice '{$invoiceTitle}' created successfully.",
            'user_id' => $userId,
        ];

        // Create the notification
        Notification::create($notificationData);
    }
}
