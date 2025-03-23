<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class UserService
{
    private $baseUrl;

    public function __construct()
    {
        $this->baseUrl = env('SPRING_API_URL', 'http://localhost:8080/api');
    }

    public function getAllUsers()
    {
        $response = Http::get("{$this->baseUrl}/users");
        
        if ($response->successful()) {
            return $response->json();
        }

        return null;
    }

    public function getUserById($id)
    {
        $response = Http::get("{$this->baseUrl}/users/{$id}");

        if ($response->successful()) {
            return $response->json();
        }

        return null;
    }

    public function createUser($data)
    {
        $response = Http::post("{$this->baseUrl}/users", $data);

        return $response->successful();
    }
}
