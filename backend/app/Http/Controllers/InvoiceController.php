<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

class InvoiceController extends Controller
{
    // Stocker une nouvelle facture
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'seller_name' => 'required|string|max:255',
            'buyer_name' => 'required|string|max:255',
            'buyer_address' => 'nullable|string|max:255', // Adresse du client si société
            'buyer_tax_id' => 'nullable|string|max:255', // Matricule fiscale
            'buyer_commercial_register' => 'nullable|string|max:255', // Registre de commerce
            'buyer_phone' => 'nullable|string|max:20', // Numéro de téléphone
            'issue_date' => 'required|date',
            'product_or_service_list' => 'required|array', // Liste de produits/services
            'product_or_service_list.*.designation' => 'required|string|max:255',
            'product_or_service_list.*.quantity' => 'required|integer|min:1',
            'product_or_service_list.*.unit_price' => 'required|numeric|min:0',
            'product_or_service_list.*.vat' => 'required|numeric|min:0', // TVA en pourcentage
            'payment_terms' => 'required|string|max:255',
            'pdf_upload' => 'nullable|mimes:pdf|max:2048',
        ]);

        $validatedData['user_id'] = Auth::id();

        // Calcul du total
        $totalHT = 0;
        $totalTVA = 0;
        foreach ($validatedData['product_or_service_list'] as &$product) {
            $product['total_price'] = $product['quantity'] * $product['unit_price'];
            $totalHT += $product['total_price'];
            $totalTVA += ($product['total_price'] * $product['vat']) / 100;
        }

        $validatedData['total_amount'] = $totalHT + $totalTVA; // Total TTC
        $validatedData['product_or_service_list'] = json_encode($validatedData['product_or_service_list']);

        // Gestion du fichier PDF
        if ($request->hasFile('pdf_upload')) {
            $path = $request->file('pdf_upload')->store('invoices', 'public');
            $validatedData['pdf_upload'] = $path;
        }

        $invoice = Invoice::create($validatedData);
        return response()->json($invoice, 201);
    }

    // Lister toutes les factures de l'utilisateur authentifié
    public function index()
    {
        $invoices = Invoice::where('user_id', Auth::id())->get();
        return response()->json($invoices);
    }

    // Obtenir une facture par ID
    public function show($id)
    {
        $invoice = Invoice::where('user_id', Auth::id())->findOrFail($id);
        return response()->json($invoice);
    }

    // Mettre à jour une facture existante
    public function update(Request $request, $id)
    {
        $invoice = Invoice::where('user_id', Auth::id())->findOrFail($id);

        $validatedData = $request->validate([
            'seller_name' => 'sometimes|required|string|max:255',
            'buyer_name' => 'sometimes|required|string|max:255',
            'buyer_address' => 'nullable|string|max:255',
            'buyer_tax_id' => 'nullable|string|max:255',
            'buyer_commercial_register' => 'nullable|string|max:255',
            'buyer_phone' => 'nullable|string|max:20',
            'issue_date' => 'sometimes|required|date',
            'product_or_service_list' => 'sometimes|required|array',
            'product_or_service_list.*.designation' => 'required|string|max:255',
            'product_or_service_list.*.quantity' => 'required|integer|min:1',
            'product_or_service_list.*.unit_price' => 'required|numeric|min:0',
            'product_or_service_list.*.vat' => 'required|numeric|min:0',
            'payment_terms' => 'sometimes|required|string|max:255',
            'pdf_upload' => 'nullable|mimes:pdf|max:2048',
        ]);

        // Gestion du fichier PDF (remplace l'ancien)
        if ($request->hasFile('pdf_upload')) {
            if ($invoice->pdf_upload) {
                Storage::disk('public')->delete($invoice->pdf_upload);
            }
            $path = $request->file('pdf_upload')->store('invoices', 'public');
            $validatedData['pdf_upload'] = $path;
        }

        // Recalcul du total si la liste des produits/services est modifiée
        if (isset($validatedData['product_or_service_list'])) {
            $totalHT = 0;
            $totalTVA = 0;
            foreach ($validatedData['product_or_service_list'] as &$product) {
                $product['total_price'] = $product['quantity'] * $product['unit_price'];
                $totalHT += $product['total_price'];
                $totalTVA += ($product['total_price'] * $product['vat']) / 100;
            }
            $validatedData['total_amount'] = $totalHT + $totalTVA;
            $validatedData['product_or_service_list'] = json_encode($validatedData['product_or_service_list']);
        }

        $invoice->update($validatedData);
        return response()->json($invoice);
    }

    // Supprimer une facture
    public function destroy($id)
    {
        $invoice = Invoice::where('user_id', Auth::id())->findOrFail($id);

        // Supprimer le fichier PDF associé
        if ($invoice->pdf_upload) {
            Storage::disk('public')->delete($invoice->pdf_upload);
        }

        $invoice->delete();
        return response()->json(['message' => 'Invoice deleted successfully'], 204);
    }

    // Télécharger une facture PDF
    public function downloadPDF($id)
    {
        $invoice = Invoice::where('user_id', Auth::id())->findOrFail($id);

        if (!$invoice->pdf_upload) {
            return response()->json(['message' => 'No PDF available for this invoice'], 404);
        }

        $filePath = storage_path("app/public/" . $invoice->pdf_upload);

        if (!file_exists($filePath)) {
            return response()->json(['message' => 'File not found'], 404);
        }

        return Response::download($filePath);
    }
}
