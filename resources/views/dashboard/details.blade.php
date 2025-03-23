@extends('template')

@section('page-title', 'Détails du ' . ucfirst($type))

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
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Détails pour le mois de <strong>{{ $month }}</strong> - <strong>{{ ucfirst($type) }}</strong></h5>

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-primary">
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Description</th>
                            <th scope="col">Montant</th>
                            <th scope="col">Date</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($details as $detail)
                            <tr>
                                <td>{{ $detail['ticketId'] ?? $detail['leadId'] ?? 'N/A' }}</td>
                                <td>{{ $detail['name'] ?? $detail['subject'] ?? 'Aucune description' }}</td>
                                <td>{{ number_format($detail['depense']['montant'] ?? 0, 0, ',', ' ') }} Ar</td>
                                <td>{{ date('d/m/Y', strtotime($detail['createdAt'])) }}</td>
                                <td>
                                    <button class="btn btn-warning btn-sm" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editModal" 
                                            onclick="openModal('{{ $detail['depense']['id'] ?? '' }}', '{{ $detail['depense']['montant'] ?? 0 }}')">
                                        <i class="bi bi-pencil-square"></i> Modifier
                                    </button>

                                    <form action="{{ route('details.destroy', ['type' => $type, 'id' => $detail['ticketId'] ?? $detail['leadId']]) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet élément ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="bi bi-trash"></i> Supprimer
                                        </button>
                                    </form>
                                </td>
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
</script>
@endsection
