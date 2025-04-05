<?php

namespace App\Http\Controllers;

use App\Models\Facture;
use App\Models\PurchaseOrder; // Import the PurchaseOrder model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Import the Auth facade

class FactureController extends Controller
{
    // Récupérer toutes les factures
    public function index()
    {
        return Facture::all();
    }

    // Créer une nouvelle facture
    public function store(Request $request)
    {
        $request->validate([
            'facture_type' => 'required|string',
            'bordereau' => 'nullable|string',
            'fournisseur' => 'nullable|string',
            'dossier' => 'nullable|string',
            'structure' => 'nullable|string',
            'direction' => 'nullable|string',
            'date_fact' => 'nullable|date',
            'period_conso' => 'nullable|string',
            'num_fact' => 'nullable|string',
            'devise' => 'nullable|string',
            'montant' => 'nullable|numeric',
            'objet' => 'nullable|string',
            'num_po' => 'nullable|string',
            'status' => 'nullable|string',
            'factname' => 'nullable|string',
            'datereception' => 'nullable|date',
            'pathpdf' => 'nullable|file|mimes:pdf|max:51200', // Max size 50MB
        ]);

        // Check if num_po exists in the PurchaseOrder table
        if ($request->num_po && !PurchaseOrder::where('num_commande', $request->num_po)->exists()) {
            return response()->json(['message' => 'Le numéro de commande fourni n\'existe pas.'], 400);
        }

        // Handle PDF upload
        if ($request->hasFile('pathpdf')) {
            $file = $request->file('pathpdf');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('factures', $filename, 'public'); // Store in storage/app/public/factures
            $request->merge(['pathpdf' => $path]);
        }

        // Get the authenticated user
        $user = Auth::user();

        // Create the facture with the current user's ID as created_by
        if (!empty($user->username)) {
            $facture = Facture::create(array_merge($request->all(), ['created_by' => $user->username]));
        } // Assuming 'username' is the field you want to use

        return response()->json($facture, 201); // Return the created facture
    }

    // Afficher une facture par ID
    public function show($id)
    {
        return Facture::findOrFail($id);
    }

    // Mettre à jour une facture
    public function update(Request $request, $id)
    {
        $facture = Facture::findOrFail($id);
        $facture->update($request->all());
        return $facture;
    }

    // Supprimer une facture
    public function destroy($id)
    {
        $facture = Facture::findOrFail($id);
        $facture->delete();
        return response()->json(['message' => 'Facture supprimée']);
    }
}
