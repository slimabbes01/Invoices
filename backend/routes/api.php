<?php

use App\Http\Controllers\PurchaseOrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FournisseurController;
use App\Http\Controllers\FactureController;
use App\Http\Controllers\AuthController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// Authentication routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);



Route::middleware('auth:sanctum')->group(function () {
    // Fournisseur routes
    Route::get('/fournisseurs', [FournisseurController::class, 'index']);
    Route::post('/fournisseurs', [FournisseurController::class, 'store']);
    Route::get('/fournisseurs/{id}', [FournisseurController::class, 'show']);
    Route::put('/fournisseurs/{id}', [FournisseurController::class, 'update']);
    Route::delete('/fournisseurs/{id}', [FournisseurController::class, 'destroy']);

    // Facture routes
    Route::get('/factures', [FactureController::class, 'index']);
    Route::post('/factures', [FactureController::class, 'store']);
    Route::get('/factures/{id}', [FactureController::class, 'show']);
    Route::put('/factures/{id}', [FactureController::class, 'update']);
    Route::delete('/factures/{id}', [FactureController::class, 'destroy']);

    Route::middleware('auth')->post('/purchase-orders', [PurchaseOrderController::class, 'store']);

});
