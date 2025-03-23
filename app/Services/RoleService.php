<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class RoleService
{
    private $baseUrl;

    public function __construct()
    {
        $this->baseUrl = env('SPRING_API_URL', 'http://localhost:8080/api');
    }

    public function getAllRoles()
    {
        $response = Http::get("{$this->baseUrl}/roles");

        if ($response->successful()) {
            return $response->json();
        }

        return null;
    }

    public function getRoleById($id)
    {
        $response = Http::get("{$this->baseUrl}/roles/{$id}");

        if ($response->successful()) {
            return $response->json();
        }

        return null;
    }

    public function createRole($data)
    {
        $response = Http::post("{$this->baseUrl}/roles", $data);

        return $response->successful();
    }

    public function deleteRole($id)
    {
        $response = Http::delete("{$this->baseUrl}/roles/{$id}");

        return $response->successful();
    }
}
