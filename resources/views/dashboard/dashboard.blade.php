@extends('template')

@section('page-title', 'Tableau de Bord')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6 mb-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Total des tickets</h5>
                    <p class="card-text fs-4 fw-bold">{{ number_format($totalMontantTicket, 0, ',', ' ') }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Total des leads</h5>
                    <p class="card-text fs-4 fw-bold">{{ number_format($totalMontantLead, 0, ',', ' ') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-12 mb-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Montants par Mois</h5>
                    <canvas id="montantChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Statuts des Leads</h5>
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // ✅ Données pour le graphique des montants par mois
    const labels = {!! json_encode(array_keys($montantParMoisTicket)) !!};
    const montantTicket = {!! json_encode(array_values($montantParMoisTicket)) !!};
    const montantLead = {!! json_encode(array_values($montantParMoisLead)) !!};

    const ctx = document.getElementById('montantChart').getContext('2d');
    const montantChart = new Chart(ctx, {
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
                    pointRadius: 5,
                    fill: false,
                    tension: 0.3
                },
                {
                    label: 'Leads',
                    data: montantLead,
                    borderColor: 'rgba(255, 99, 132, 1)',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderWidth: 2,
                    pointRadius: 5,
                    fill: false,
                    tension: 0.3
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // ✅ Données pour le camembert des statuts des leads
    const statutLabels = {!! json_encode(array_keys($statutLeads)) !!};
    const statutData = {!! json_encode(array_values($statutLeads)) !!};
    
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    const statusChart = new Chart(statusCtx, {
        type: 'pie', // 🥧 Camembert
        data: {
            labels: statutLabels,
            datasets: [{
                label: 'Statuts des Leads',
                data: statutData,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.6)', // Rouge
                    'rgba(54, 162, 235, 0.6)', // Bleu
                    'rgba(255, 206, 86, 0.6)', // Jaune
                    'rgba(75, 192, 192, 0.6)', // Vert
                    'rgba(153, 102, 255, 0.6)', // Violet
                    'rgba(255, 159, 64, 0.6)'  // Orange
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            }
        }
    });
</script>
@endsection
