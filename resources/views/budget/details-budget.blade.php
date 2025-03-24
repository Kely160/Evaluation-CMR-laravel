@extends('template')

@section('page-title', 'Détails budget')

@section('content')
<div class="pagetitle">
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Tableau de Bord</a></li>
            <li class="breadcrumb-item active">Détails</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">
                        Détails budget
                    </h5>

                    <div class="table-responsive">
                        <table class="table dataTable table-bordered table-striped">
                            <thead class="table-primary">
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">Client</th>
                                    <th scope="col">Description</th>
                                    <th scope="col">Montant</th>
                                    <th scope="col">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($details as $detail)
                                    <tr>
                                        <td>{{ $detail['id']}}</td>
                                        <td>{{ $detail['customer']['name']}}</td>
                                        <td>{{ $detail['description'] ?? 'Aucune description' }}</td>
                                        <td class="text-end">{{ number_format($detail['montant'] ?? 0, 0, ',', ' ') }}</td>
                                        <td>{{ date('d/m/Y', strtotime($detail['dateCreation'])) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-danger">
                                            <i class="bi bi-exclamation-triangle"></i> Aucun détail trouvé pour ce mois.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end mt-3">
                        <a href="{{ route('dashboard') }}" class="btn btn-primary">
                            <i class="bi bi-arrow-left-circle"></i> Retour
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('update-depense') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Modifier le Montant</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="detailId">
                    <div class="mb-3">
                        <label for="montant" class="form-label">Nouveau Montant</label>
                        <input type="number" class="form-control" name="montant" id="montant" min="0" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openModal(id, montant) {
        document.getElementById('detailId').value = id;
        document.getElementById('montant').value = montant;
    }

    // Initialisation du DataTable
    document.addEventListener('DOMContentLoaded', function() {
        const table = document.querySelector('.dataTable');
        if (table) {
            new simpleDatatables.DataTable(table, {
                searchable: true,
                fixedHeight: true,
                perPage: 10,
                labels: {
                    placeholder: "Rechercher...",
                    perPage: "{select} résultats par page",
                    noRows: "Aucun résultat trouvé",
                    info: "Affichage de {start} à {end} sur {rows} résultats"
                }
            });
        }
    });
</script>
@endsection
