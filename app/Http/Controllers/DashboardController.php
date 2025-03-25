<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $customers = Http::get("http://localhost:8080/api/admin/client");
        $tickets = Http::get("http://localhost:8080/api/admin/ticket");
        $leads = Http::get("http://localhost:8080/api/admin/lead");
        $budgets = Http::get("http://localhost:8080/api/admin/budget");
        $depenses = Http::get("http://localhost:8080/api/admin/depense");

        $totalBudget = 0;
        $totalDepense = 0;
        $totalMontantTicket = 0;
        $totalMontantLead = 0;
        $montantParMoisTicket = [];
        $montantParMoisLead = [];
        $statutLeads = [];
        $budgetDepenseParClient = [];

        $now = now();
        $startOfYear = $now->copy()->startOfYear();
        $months = [];

        for ($i = 0; $i < 12; $i++) {
            $months[$startOfYear->copy()->addMonths($i)->format('Y-m')] = 0;
        }

        if ($budgets->successful()) {
            $budgetData = $budgets->json();
            if (is_array($budgetData)) {
                $totalBudget = array_sum(array_map(function ($budget) {
                    return $budget['montant'] ?? 0;
                }, $budgetData));
            }
        }

        if ($depenses->successful()) {
            $depenseData = $depenses->json();
            if (is_array($depenseData)) {
                $totalDepense = array_sum(array_map(function ($depense) {
                    return $depense['montant'] ?? 0;
                }, $depenseData));
            }
        }

        if ($tickets->successful()) {
            $ticketData = $tickets->json();
            if (is_array($ticketData)) {
                $groupedTickets = collect($ticketData)
                    ->groupBy(function ($ticket) {
                        return date('Y-m', strtotime($ticket['createdAt']));
                    });

                $ticketSums = $groupedTickets->map(function ($items) {
                    return $items->sum(function ($ticket) {
                        return isset($ticket['depense']['montant']) ? $ticket['depense']['montant'] : 0;
                    });
                })->toArray();

                $montantParMoisTicket = array_merge($months, $ticketSums);
                $totalMontantTicket = array_sum($montantParMoisTicket);
            }
        }

        if ($leads->successful()) {
            $leadData = $leads->json();
            if (is_array($leadData)) {
                $groupedLeads = collect($leadData)
                    ->groupBy(function ($lead) {
                        return date('Y-m', strtotime($lead['createdAt']));
                    });

                $leadSums = $groupedLeads->map(function ($items) {
                    return $items->sum(function ($lead) {
                        return isset($lead['depense']['montant']) ? $lead['depense']['montant'] : 0;
                    });
                })->toArray();

                $statutLeads = collect($leadData)
                    ->groupBy('status')
                    ->map(function ($items) {
                        return count($items);
                    })
                    ->toArray();

                $montantParMoisLead = array_merge($months, $leadSums);
                $totalMontantLead = array_sum($montantParMoisLead);
            }
        }   

        if ($budgets->successful() && $customers->successful() && $depenses->successful()) {
            $budgetData = $budgets->json();
            $depenseData = $depenses->json();
            $customerData = $customers->json();
    
            $totalBudget = array_sum(array_column($budgetData, 'montant'));
            
            // 🏷️ Initialisation des données par client
            foreach ($customerData as $customer) {
                $clientId = $customer['customerId'];
                $budgetDepenseParClient[$customer['name']] = [
                    'budget' => 0,
                    'depense' => 0,
                ];
            }
    
            // 🏷️ Agrégation des budgets par client
            foreach ($budgetData as $budget) {
                $clientName = $budget['customer']['name'] ?? 'Inconnu';
                $budgetDepenseParClient[$clientName]['budget'] += $budget['montant'] ?? 0;
            }
    
            // 🏷️ Agrégation des dépenses par client
            foreach ($depenseData as $depense) {
                $clientName = $depense['customer']['name'] ?? 'Inconnu';
                $budgetDepenseParClient[$clientName]['depense'] += $depense['montant'] ?? 0;
            }
        }

        return view('dashboard.dashboard', compact(
            'totalBudget',
            'totalDepense',
            'totalMontantTicket',
            'totalMontantLead',
            'montantParMoisTicket',
            'montantParMoisLead',
            'statutLeads',
            'budgetDepenseParClient'
        ));
    }

    public function detailsTotal($type)
    {
        $apiUrl = "http://localhost:8080/api/admin/";
        $response = Http::get($apiUrl . ($type === 'tickets' ? 'ticket' : 'lead'));

        $details = [];
        if ($response->successful()) {
            $details = $response->json();
        }

        return view('dashboard.details', compact('details', 'type'));
    }

    public function detailsBudget()
    {
        $apiUrl = "http://localhost:8080/api/admin/";
        $response = Http::get($apiUrl . 'budget');

        $details = [];
        if ($response->successful()) {
            $details = $response->json();
        }

        return view('budget.details-budget', compact('details'));
    }

    public function details($type, $month)
    {
        $apiUrl = "http://localhost:8080/api/admin/";
        $response = Http::get($apiUrl . ($type === 'tickets' ? 'ticket' : 'lead'));

        $details = [];
        if ($response->successful()) {
            $data = $response->json();
            $details = collect($data)->filter(function ($item) use ($month) {
                return date('Y-m', strtotime($item['createdAt'])) === $month;
            });
        }

        return view('dashboard.details', compact('details', 'type', 'month'));
    }

    public function destroy($type, $id)
    {
        $apiUrl = "http://localhost:8080/api/admin/" . ($type === 'tickets' ? 'ticket' : 'lead') . "/delete/$id";
        $response = Http::post($apiUrl);

        if ($response->successful()) {
            return redirect()->route('dashboard')->with('success', 'Détail supprimé avec succès.');
        } else {
            return back()->with('error', 'Échec de la suppression. Veuillez réessayer.');
        }
    }
}
