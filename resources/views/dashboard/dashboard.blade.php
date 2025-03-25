@extends('template')

@section('page-title', 'Tableau de Bord')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-4 mb-3">
            <a href="{{ route('details-budget') }}">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Total des budgets</h5>
                    <p class="card-text fs-4 fw-bold">{{ number_format($totalBudget, 0, ',', ' ') }}</p>
                </div>
            </div>
            </a>
        </div>

        <div class="col-md-4 mb-3">
            <a href="{{ route('details-total', ['type' => 'tickets']) }}">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Total des tickets</h5>
                    <p class="card-text fs-4 fw-bold">{{ number_format($totalMontantTicket, 0, ',', ' ') }}</p>
                </div>
            </div>
            </a>
        </div>

        <div class="col-md-4 mb-3">
            <a href="{{ route('details-total', ['type' => 'leads']) }}">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Total des leads</h5>
                    <p class="card-text fs-4 fw-bold">{{ number_format($totalMontantLead, 0, ',', ' ') }}</p>
                </div>
            </div>
            </a>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Montants par mois</h5>
                    <canvas id="montantChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Dépenses et Budgets par Client</h5>
                    <canvas id="budgetDepenseChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Répartition des dépenses</h5>
                    <canvas id="depensePieChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
{{ $totalDepense }}

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const labels = {!! json_encode(array_keys($montantParMoisTicket)) !!};
    const montantTicket = {!! json_encode(array_values($montantParMoisTicket)) !!};
    const montantLead = {!! json_encode(array_values($montantParMoisLead)) !!};
    
    const ctx = document.getElementById('montantChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Tickets',
                    data: montantTicket,
                    borderColor: 'rgba(54, 162, 235, 1)',
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderWidth: 2,
                    tension: 0.3,
                },
                {
                    label: 'Leads',
                    data: montantLead,
                    borderColor: 'rgba(255, 99, 132, 1)',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderWidth: 2,
                    tension: 0.3,
                }
            ]
        },
        options: { responsive: true }
    });

    // ✅ Données pour l'histogramme dépenses vs budget par client
    const clientLabels = {!! json_encode(array_keys($budgetDepenseParClient)) !!};
    const budgetData = {!! json_encode(array_column($budgetDepenseParClient, 'budget')) !!};
    const depenseData = {!! json_encode(array_column($budgetDepenseParClient, 'depense')) !!};

    const budgetDepenseCtx = document.getElementById('budgetDepenseChart').getContext('2d');
    new Chart(budgetDepenseCtx, {
        type: 'bar',
        data: {
            labels: clientLabels,
            datasets: [
                {
                    label: 'Budget',
                    data: budgetData,
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1,
                },
                {
                    label: 'Dépense',
                    data: depenseData,
                    backgroundColor: 'rgba(255, 99, 132, 0.6)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1,
                }
            ]
        },
        options: {
            responsive: true,
            scales: { y: { beginAtZero: true } }
        }
    });

    
    const totalDepense = {!! $totalDepense !!};
    const totalTicket = {!! $totalMontantTicket !!};
    const totalLead = {!! $totalMontantLead !!};
    const autre = totalDepense - (totalTicket + totalLead);

    document.addEventListener("DOMContentLoaded", () => {
        new Chart(document.querySelector('#depensePieChart'), {
            type: 'pie',
            data: {
                labels: [
                    'Dépense sur les tickets',
                    'Dépense sur les leads',
                    'Autre'
                ],
                datasets: [{
                    data: [totalTicket, totalLead, autre],
                    backgroundColor: [
                        'rgb(255, 99, 132)',
                        'rgb(54, 162, 235)',
                        'rgb(255, 205, 86)'
                    ],
                    hoverOffset: 4
                }]
            }
        });
    });
</script>
@endsection
