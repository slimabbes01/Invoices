<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseOrderController extends Controller
{
    // Récupérer toutes les commandes d'achat
    public function index()
    {
        return PurchaseOrder::all();
    }

    // Créer une nouvelle commande d'achat
    public function store(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'delai_paiement' => 'required|string',
            'iderp' => 'required|string',
        ]);

        // Get the authenticated user
        $user = Auth::user();

        // Check if user is authenticated
        if (!$user) {
            return response()->json(['message' => 'Utilisateur non authentifié'], 401); // User not authenticated
        }

        // Get the latest purchase order and increment the num_commande
        $lastOrder = PurchaseOrder::orderBy('id', 'desc')->first();
        $nextNumCommande = $lastOrder ? $lastOrder->num_commande + 1 : 1000001;

        // Get the authenticated user's username
        if (!empty($user->username)) {
            $createdBy = $user->username;
        } // This should work if username is defined correctly

        // Create a new purchase order
        $purchaseOrder = PurchaseOrder::create([
            'num_commande' => $nextNumCommande,
            'iderp' => $request->iderp,
            'delai_paiement' => $request->delai_paiement,
            'created_by' => $createdBy, // Set created_by to the authenticated user's username
        ]);

        return response()->json(['purchase_order' => $purchaseOrder], 201); // 201 Created
    }

    // Afficher une commande d'achat par ID
    public function show($id)
    {
        return PurchaseOrder::findOrFail($id);
    }

    // Mettre à jour une commande d'achat
    public function update(Request $request, $id)
    {
        $purchaseOrder = PurchaseOrder::findOrFail($id);
        $purchaseOrder->update($request->all());
        return $purchaseOrder;
    }

    // Supprimer une commande d'achat
    public function destroy($id)
    {
        $purchaseOrder = PurchaseOrder::findOrFail($id);
        $purchaseOrder->delete();
        return response()->json(['message' => 'Commande d\'achat supprimée']);
    }
}
