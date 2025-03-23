<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index() {
        $customers = Http::get("http://localhost:8080/api/admin/client");
        $tickets = Http::get("http://localhost:8080/api/admin/ticket");
        $leads = Http::get("http://localhost:8080/api/admin/lead");
        $budgets = Http::get("http://localhost:8080/api/admin/budget");

        $totalMontantTicket = 0;
        $totalMontantLead = 0;
        $montantParMoisTicket = [];
        $montantParMoisLead = [];

        $now = now();
        $startOfYear = $now->copy()->startOfYear(); // 1er Janvier de l'année en cours
        $months = [];

        // ✅ Génère tous les mois de Janvier à Décembre
        for ($i = 0; $i < 12; $i++) {
            $months[$startOfYear->copy()->addMonths($i)->format('Y-m')] = 0;
        }

        // ✅ Groupement des montants de tickets
        if ($tickets->successful()) {
            $ticketData = $tickets->json();
            if (is_array($ticketData)) {
                $groupedTickets = collect($ticketData)
                    ->groupBy(function($ticket) {
                        return date('Y-m', strtotime($ticket['createdAt']));
                    });

                // ✅ Additionne les montants par mois
                $ticketSums = $groupedTickets->map(function($items) {
                    return $items->sum(function($ticket) {
                        return isset($ticket['depense']['montant']) ? $ticket['depense']['montant'] : 0;
                    });
                })->toArray();

                // ✅ Fusionne avec tous les mois de l'année
                $montantParMoisTicket = array_merge($months, $ticketSums);
                $totalMontantTicket = array_sum($montantParMoisTicket);
            }
        }

        // ✅ Groupement des montants de leads
        if ($leads->successful()) {
            $leadData = $leads->json();
            if (is_array($leadData)) {
                $groupedLeads = collect($leadData)
                    ->groupBy(function($lead) {
                        return date('Y-m', strtotime($lead['createdAt']));
                    });

                // ✅ Additionne les montants par mois
                $leadSums = $groupedLeads->map(function($items) {
                    return $items->sum(function($lead) {
                        return isset($lead['depense']['montant']) ? $lead['depense']['montant'] : 0;
                    });
                })->toArray();

                // ✅ Fusionne avec tous les mois de l'année
                $montantParMoisLead = array_merge($months, $leadSums);
                $totalMontantLead = array_sum($montantParMoisLead);
            }
        }

        return view('dashboard.dashboard', compact(
            'totalMontantTicket',
            'totalMontantLead',
            'montantParMoisTicket',
            'montantParMoisLead'
        ));
    }

    public function details($type, $month) {
        $apiUrl = "http://localhost:8080/api/admin/";
        $response = Http::get($apiUrl . ($type === 'tickets' ? 'ticket' : 'lead'));
    
        $details = [];
        if ($response->successful()) {
            $data = $response->json();
            $details = collect($data)->filter(function($item) use ($month) {
                return date('Y-m', strtotime($item['createdAt'])) === $month;
            });
        }
        
        return view('dashboard.details', compact('details', 'type', 'month'));
    }    

    public function destroy($type, $id) {
        $apiUrl = "http://localhost:8080/api/admin/" . ($type === 'tickets' ? 'ticket' : 'lead') . "/delete/$id";
        $response = Http::post($apiUrl);
    
        if ($response->successful()) {
            return redirect()->route('dashboard')->with('success', 'Détail supprimé avec succès.');
        } else {
            return back()->with('error', 'Échec de la suppression. Veuillez réessayer.');
        }
    }    
}
