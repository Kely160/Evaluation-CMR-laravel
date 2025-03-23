<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    public function checkLogin(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = [
            'username' => $request->input('username'),
            'password' => $request->input('password')
        ];

        try {
            // Remplacez l'URL par votre API Spring Boot
            $response = Http::post('http://localhost:8080/api/auth/admin', $credentials);
            
            if ($response->successful()) {
                $user = $response->json();
                
                if ($user) {
                    session(['user' => $user]);
                    return redirect()->route('template')->with('success', 'Connexion réussie.');
                } else {
                    return back()->withErrors(['error' => 'Utilisateur non trouvé.']);
                }
            } else {
                return back()->withErrors(['error' => 'Nom d\'utilisateur ou mot de passe incorrect.']);
            }
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur serveur : ' . $e->getMessage()]);
        }
    }
}
