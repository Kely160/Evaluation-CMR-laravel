<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class UserProfileService
{
    private $baseUrl;

    public function __construct()
    {
        $this->baseUrl = env('SPRING_API_URL', 'http://localhost:8080/api');
    }

    /**
     * Récupère tous les profils utilisateurs
     */
    public function getAllProfiles()
    {
        $response = Http::get("{$this->baseUrl}/user-profiles");

        if ($response->successful()) {
            return $response->json();
        }

        return null;
    }

    /**
     * Récupère un profil utilisateur par ID
     */
    public function getProfileById($id)
    {
        $response = Http::get("{$this->baseUrl}/user-profiles/{$id}");

        if ($response->successful()) {
            return $response->json();
        }

        return null;
    }

    /**
     * Crée un profil utilisateur
     */
    public function createProfile($data)
    {
        $response = Http::post("{$this->baseUrl}/user-profiles", $data);

        return $response->successful();
    }

    /**
     * Met à jour un profil utilisateur
     */
    public function updateProfile($id, $data)
    {
        $response = Http::put("{$this->baseUrl}/user-profiles/{$id}", $data);

        return $response->successful();
    }

    /**
     * Supprime un profil utilisateur
     */
    public function deleteProfile($id)
    {
        $response = Http::delete("{$this->baseUrl}/user-profiles/{$id}");

        return $response->successful();
    }
}
