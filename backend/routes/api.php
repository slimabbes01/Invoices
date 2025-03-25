<?php

use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Registration route
Route::post('register', [AuthController::class, 'register']);

// Login route
Route::post('login', [AuthController::class, 'login']);

// Custom email verification route
Route::get('/verify-email/{token}', [AuthController::class, 'verifyEmail'])
    ->name('verification.verify');

// Authenticated user route
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// User profile routes (Get, Update, Change Password, Upload Photo)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [UserController::class, 'getUser']); // Get user profile
    Route::put('/user', [UserController::class, 'updateUser']); // Update profile
    Route::post('/user/change-password', [UserController::class, 'changePassword']); // Change password
});

// Invoice routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/invoices', [InvoiceController::class, 'store']); // Create invoice
    Route::get('/invoices/{id}', [InvoiceController::class, 'show']); // Get invoice
    Route::put('/invoices/{id}', [InvoiceController::class, 'update']); // Update invoice
    Route::delete('/invoices/{id}', [InvoiceController::class, 'destroy']); // Delete invoice
    Route::get('/invoices', [InvoiceController::class, 'index']); // Get all invoices
    Route::get('/invoices/{id}/download', [InvoiceController::class, 'downloadPDF']);
});

// Notification routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/notifications', [NotificationController::class, 'store']); // Create notification
    Route::get('/notifications', [NotificationController::class, 'index']); // Get all notifications
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy']); // Delete notification
});

// Role routes
Route::middleware('auth:sanctum')->apiResource('roles', RoleController::class); // Resource routes for roles

// Permission routes
Route::middleware('auth:sanctum')->apiResource('permissions', PermissionController::class); // Resource routes for permissions

// Forgot password
Route::post('/password/email', [ResetPasswordController::class, 'sendResetLinkEmail']);
Route::post('/password/reset', [ResetPasswordController::class, 'reset']);
Route::get('/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
