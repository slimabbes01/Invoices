<?php

namespace App\Http\Controllers;

use App\Models\Fournisseur;
use Illuminate\Http\Request;

class FournisseurController extends Controller
{
    public function index()
    {
        // Return all suppliers
        return Fournisseur::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'iderp' => 'required|string|unique:fournisseur',
            'name' => 'required|string',
            'idfiscale' => 'required|string',
            'adresse' => 'required|string',
            'nationnalite' => 'required|string',
            'created_by' => 'required|string', // Update this line
        ]);

        return Fournisseur::create($request->all());
    }


    public function show($id)
    {
        // Return a specific supplier by ID
        return Fournisseur::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        // Find the supplier and update with the request data
        $fournisseur = Fournisseur::findOrFail($id);
        $fournisseur->update($request->all());
        return $fournisseur;
    }

    public function destroy($id)
    {
        // Find the supplier and delete it
        $fournisseur = Fournisseur::findOrFail($id);
        $fournisseur->delete();
        return response()->json(['message' => 'Fournisseur deleted']);
    }
}
