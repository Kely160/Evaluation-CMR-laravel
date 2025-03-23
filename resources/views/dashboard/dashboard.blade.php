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
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Montants par Mois</h5>
                    <canvas id="montantChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Données formatées avec tous les mois de l'année
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
            },
            onClick: (e) => {
                const points = montantChart.getElementsAtEventForMode(e, 'nearest', { intersect: true }, true);
                if (points.length) {
                    const firstPoint = points[0];
                    const label = montantChart.data.labels[firstPoint.index];
                    const datasetLabel = montantChart.data.datasets[firstPoint.datasetIndex].label;
                    
                    // Redirige vers la page de détails avec les paramètres
                    const url = `/details/${datasetLabel.toLowerCase()}/${label}`;
                    window.location.href = url;
                }
            }
        }
    });
</script>
@endsection
