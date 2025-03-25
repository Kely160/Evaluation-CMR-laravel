<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TauxAlerteController extends Controller
{
    public function index() {
        return view('tauxAlerte.configuration');
    }

    public function create(Request $request) {
        // Validation des données
        $request->validate([
            'taux' => 'required|min:0',
            'date_modification' => 'required|date',
        ]);

        // Préparation des données à envoyer
        $data = [
            'taux' => $request->input('taux'),
            'dateModification' => $request->input('date_modification'),
        ];

        try {
            // Appel à l'API pour créer un nouveau TauxAlerte
            $response = Http::post('http://localhost:8080/api/taux-alerte', $data);
            
            if ($response->successful()) {
                return redirect()->back()->with('success', 'Taux Alerte créé avec succès.');
            } else {
                return redirect()->back()->with('error', 'Échec de la création du Taux Alerte.');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur : ' . $e->getMessage());
        }
    }
}
