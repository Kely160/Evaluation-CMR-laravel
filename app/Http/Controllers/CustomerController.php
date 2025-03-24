<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Exception;

class CustomerController extends Controller
{
    private $apiUrl = "http://localhost:8080/api/admin/client"; // Ajustez selon votre config

    // 👉 Lister tous les clients
    public function index()
    {
        try {
            $response = Http::get($this->apiUrl);
            
            if ($response->successful()) {
                $customers = $response->json();
                dump($customers);
                // return view('customers.index', compact('customers'));
            } else {
                dump("Erreur : " . $response->status());
                // return back()->with('error', 'Impossible de récupérer les clients');
            }
        } catch (Exception $e) {
            dump("Exception : " . $e->getMessage());
            // return back()->with('error', 'Une erreur s\'est produite lors de la récupération des clients');
        }
    }

    // 👉 Afficher un client spécifique
    public function show($id)
    {
        try {
            $response = Http::get("{$this->apiUrl}/{$id}");

            if ($response->successful()) {
                $customer = $response->json();
                dump($customer);
                // return view('customers.show', compact('customer'));
            } else {
                dump("Erreur : " . $response->status());
                // return back()->with('error', 'Client non trouvé');
            }
        } catch (Exception $e) {
            dump("Exception : " . $e->getMessage());
            // return back()->with('error', 'Une erreur s\'est produite lors de la récupération du client');
        }
    }

    // 👉 Créer un nouveau client
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email',
            'phone' => 'required|string|max:15',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'state' => 'nullable|string',
            'country' => 'nullable|string',
        ]);

        try {
            $response = Http::post($this->apiUrl, $validated);

            if ($response->successful()) {
                dump("Client créé avec succès");
                // return redirect()->route('customers.index')->with('success', 'Client créé avec succès');
            } else {
                dump("Erreur : " . $response->status());
                // return back()->with('error', 'Échec de la création du client');
            }
        } catch (Exception $e) {
            dump("Exception : " . $e->getMessage());
            // return back()->with('error', 'Une erreur s\'est produite lors de la création du client');
        }
    }

    // 👉 Mettre à jour un client
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:15',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'state' => 'nullable|string',
            'country' => 'nullable|string',
        ]);

        try {
            $response = Http::put("{$this->apiUrl}/update/{$id}", $validated);

            if ($response->successful()) {
                dump("Client mis à jour avec succès");
                // return redirect()->route('customers.index')->with('success', 'Client mis à jour avec succès');
            } else {
                dump("Erreur : " . $response->status());
                // return back()->with('error', 'Échec de la mise à jour du client');
            }
        } catch (Exception $e) {
            dump("Exception : " . $e->getMessage());
            // return back()->with('error', 'Une erreur s\'est produite lors de la mise à jour du client');
        }
    }

    // 👉 Supprimer un client
    public function destroy($id)
    {
        try {
            $response = Http::delete("{$this->apiUrl}/delete/{$id}");

            if ($response->successful()) {
                dd("Client supprimé avec succès");
                // return redirect()->route('customers.index')->with('success', 'Client supprimé avec succès');
            } else {
                dump("Erreur : " . $response->status());
                // return back()->with('error', 'Échec de la suppression du client');
            }
        } catch (Exception $e) {
            dump("Exception : " . $e->getMessage());
            // return back()->with('error', 'Une erreur s\'est produite lors de la suppression du client');
        }
    }
}
