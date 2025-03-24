<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DepenseController extends Controller
{
    public function update(Request $request) {
        // Validation des données
        $request->validate([
            'id' => 'required|integer',
            'montant' => 'required|numeric|min:0',
        ]);
    
        $id = $request->input('id');
        $montant = $request->input('montant');

        $apiUrl = "http://localhost:8080/api/admin/depense/update/$id";
    
        try {
            $response = Http::post($apiUrl, [
                'montant' => $montant,
            ]);
            
            if ($response->successful()) {
                return redirect()->back()->with('success', 'Montant mis à jour avec succès.');
            } else {
                $errorMessage = $response->json()['message'] ?? 'Erreur inconnue';
                return back()->with('error', "Échec de la mise à jour : $errorMessage");
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur de connexion à l\'API : ' . $e->getMessage());
        }
    }    
}
